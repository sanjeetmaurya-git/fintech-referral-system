<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\ReferralLogModel;
use App\Models\SettingModel;
use App\Models\NotificationModel;
use Config\Database;

class ReferralService
{
    protected $settingModel;
    protected $userModel;
    protected $walletModel;
    protected $transactionModel;
    protected $referralLogModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->settingModel     = new SettingModel();
        $this->userModel        = new UserModel();
        $this->walletModel      = new WalletModel();
        $this->transactionModel = new WalletTransactionModel();
        $this->referralLogModel = new ReferralLogModel();
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Process referral for a newly registered user
     */
    public function processReferral(int $newUserId, ?string $referralCode, string $ipAddress)
    {
        if (empty($referralCode)) {
            // New requirement: 95/5 split if no referral
            $this->processNoReferralReward($newUserId);
            return;
        }

        // Find referrer by code
        $referrer = $this->userModel->where('referral_code', $referralCode)->first();

        if (!$referrer) {
            return; // Invalid referral code
        }

        // Prevent self-referral (Phase 4 requirement)
        if ($referrer['id'] == $newUserId) {
            return;
        }

        // Phase 6: Prevent multiple rewards from same device
        $newUser = $this->userModel->find($newUserId);
        if ($newUser && !empty($newUser['device_id'])) {
            $existingUserWithDevice = $this->userModel
                ->where('device_id', $newUser['device_id'])
                ->where('id !=', $newUserId)
                ->first();
            
            if ($existingUserWithDevice) {
                // Device already used for a registration
                // We logic: Log the attempt but don't distribute rewards
                $this->referralLogModel->insert([
                    'referrer_id'      => $referrer['id'],
                    'referred_user_id' => $newUserId,
                    'referral_code'    => $referralCode,
                    'ip_address'       => $ipAddress,
                    'description'      => 'Fraud Attempt: Duplicate Device ID'
                ]);
                return;
            }
        }

        // 1. Update the referred user's 'referred_by' field
        $this->userModel->update($newUserId, ['referred_by' => $referrer['id']]);

        // 2. Log the referral (Phase 4 requirement)
        $this->referralLogModel->insert([
            'referrer_id'      => $referrer['id'],
            'referred_user_id' => $newUserId,
            'referral_code'    => $referralCode,
            'ip_address'       => $ipAddress,
        ]);

        // 3. Distribute Multi-Level Rewards (Phase 5 requirement)
        $this->distributeRewards($newUserId);
    }

    /**
     * Distribute rewards up to 8 levels with dynamic redistribution
     */
    protected function distributeRewards(int $userId)
    {
        $db = Database::connect();
        $db->transStart();

        $rewardPercentages = $this->settingModel->getRewardPercentages();
        $maxLevels = count($rewardPercentages);
        $baseReward = (float) $this->settingModel->getVal('base_reward_amount', 10.00); 

        // 1. Identify the referral chain
        $chain = [];
        $currentUserId = $userId;
        while (count($chain) < $maxLevels) {
            $user = $this->userModel->find($currentUserId);
            if (!$user || empty($user['referred_by'])) break;
            
            $chain[] = $user['referred_by'];
            $currentUserId = $user['referred_by'];
        }

        $chainCount = count($chain);
        if ($chainCount === 0) {
            $this->processNoReferralReward($userId);
            $db->transComplete();
            return;
        }

        // 2. Calculate Redistribution
        $totalPotentialPercentage = array_sum($rewardPercentages);
        $usedBasePercentage = array_sum(array_slice($rewardPercentages, 0, $chainCount));
        $remainingPercentage = $totalPotentialPercentage - $usedBasePercentage;
        $bonusPerLevel = $remainingPercentage / $chainCount;

        // 3. Distribute
        $totalDistributed = 0;
        foreach ($chain as $index => $referrerId) {
            $baseLevelPercent = $rewardPercentages[$index];
            $finalPercent = $baseLevelPercent + $bonusPerLevel;
            
            // Round to 2 decimals
            $rewardAmount = round(($baseReward * $finalPercent) / 100, 2);
            $totalDistributed += $rewardAmount;

            if ($rewardAmount > 0) {
                $this->creditReward($referrerId, $rewardAmount, $userId, $index + 1);
            }
        }

        // 4. Rounding Remainder to Admin
        $remainder = round($baseReward - $totalDistributed, 2);
        if ($remainder > 0) {
            $admin = $this->userModel->where('is_admin', 1)->orderBy('id', 'ASC')->first();
            if ($admin) {
                $this->creditReward($admin['id'], $remainder, $userId, 0, "Rounding Remainder to Admin");
            }
        }

        $db->transComplete();
    }

    protected function creditReward(int $referrerId, float $amount, int $originUserId, int $level, string $description = null)
    {
        // 1. Get or Create Wallet
        $wallet = $this->walletModel->where('user_id', $referrerId)->first();
        
        if (!$wallet) {
            $this->walletModel->insert([
                'user_id' => $referrerId,
                'balance' => 0.00,
                'coins'   => 0.00
            ]);
            $walletId = $this->walletModel->getInsertID();
        } else {
            $walletId = $wallet['id'];
        }

        // 2. Check Reward Mode
        $rewardMode = $this->settingModel->getVal('reward_mode', 'balance');
        $coinValue  = (float) $this->settingModel->getVal('coin_value', 1.00);
        
        $finalAmount = $amount;
        $refPrefix   = 'REF-';
        $desc        = $description ?? "Referral reward Level {$level} from User ID {$originUserId}";

        if ($rewardMode === 'coins' && $coinValue > 0) {
            $coinAmount = round($amount / $coinValue);
            $finalAmount = $coinAmount;
            $refPrefix   = 'REFC-'; // Using REFC to denote Coin Reward
            $desc        = ($description ?? "Referral reward Level {$level} from User ID {$originUserId}") . " ({$coinAmount} Coins)";
        }

        // 3. Log Transaction as PENDING
        $this->transactionModel->insert([
            'user_id'      => $referrerId,
            'type'         => 'credit',
            'amount'       => $finalAmount,
            'reference_id' => $refPrefix . $originUserId . ($level > 0 ? '-L' . $level : '-NOREF'),
            'description'  => $desc,
            'status'       => 'pending', 
        ]);
    }

    /**
     * Handle 95/5 split for users who register without a referral code.
     */
    protected function processNoReferralReward(int $userId)
    {
        $baseReward = (float) $this->settingModel->getVal('base_reward_amount', 10.00);
        
        $userReward = $baseReward * 0.95;
        $adminReward = $baseReward * 0.05;

        // Credit User (95%)
        $this->creditReward($userId, $userReward, $userId, 0, "Welcome Reward (95% No-Referral split)");

        // Credit Admin (5%)
        $admin = $this->userModel->where('is_admin', 1)->orderBy('id', 'ASC')->first();
        if ($admin) {
            $this->creditReward($admin['id'], $adminReward, $userId, 0, "Admin Commission (5% No-Referral split from User {$userId})");
        }
    }

    /**
     * Process rewards for a transaction (e.g. membership upgrade)
     * 1st Transaction: 1:1 coins to the user.
     * Subsequent: Tiered coins split among referrers.
     */
    public function processTransactionRewards(int $userId, float $amount)
    {
        $user = $this->userModel->find($userId);
        if (!$user) return;

        $db = Database::connect();
        $db->transStart();

        if ((int)$user['has_done_first_tx'] === 0) {
            // --- FIRST TRANSACTION: 1:1 Reward to User ---
            $ledger = new \App\Services\LedgerService();
            $ledger->record(
                $userId, 
                'credit', 
                $amount, 
                'FIRST-TX-' . time(), 
                "First Transaction 1:1 Reward ({$amount} Coins)",
                'coins'
            );

            // Notify user
            $this->notificationModel->notify(
                $userId,
                "First Transaction Reward!",
                "You received {$amount} coins as a 1:1 reward for your first transaction.",
                'reward',
                'bi-gift-fill'
            );

            // Mark as done
            $this->userModel->update($userId, ['has_done_first_tx' => 1]);
        } else {
            // --- SUBSEQUENT: Tiered MLM Reward ---
            $tierModel = new \App\Models\TransactionRewardTierModel();
            $applicableTier = $tierModel
                ->where('min_amount <=', $amount)
                ->where('max_amount >=', $amount)
                ->first();

            if ($applicableTier && (float)$applicableTier['reward_coins'] > 0) {
                $this->distributeCoinRewards($userId, (float)$applicableTier['reward_coins']);
            }
        }

        $db->transComplete();
    }

    /**
     * Distribute MLM Coin Rewards with dynamic redistribution using LedgerService
     */
    public function distributeCoinRewards(int $userId, float $totalCoins)
    {
        $db = Database::connect();
        $db->transStart();

        $rewardPercentages = $this->settingModel->getRewardPercentages();
        $maxLevels = count($rewardPercentages);
        $ledger = new \App\Services\LedgerService();

        // 1. Identify the referral chain
        $chain = [];
        $currentUserId = $userId;
        while (count($chain) < $maxLevels) {
            $user = $this->userModel->find($currentUserId);
            if (!$user || empty($user['referred_by'])) break;
            
            $chain[] = $user['referred_by'];
            $currentUserId = $user['referred_by'];
        }

        $chainCount = count($chain);
        if ($chainCount === 0) {
            $db->transComplete();
            return;
        }

        // 2. Calculate Redistribution
        $totalPotentialPercentage = array_sum($rewardPercentages);
        $usedBasePercentage = array_sum(array_slice($rewardPercentages, 0, $chainCount));
        $remainingPercentage = $totalPotentialPercentage - $usedBasePercentage;
        $bonusPerLevel = $remainingPercentage / $chainCount;

        // 3. Distribute
        $totalDistributed = 0;
        foreach ($chain as $index => $referrerId) {
            $baseLevelPercent = $rewardPercentages[$index];
            $finalPercent = $baseLevelPercent + $bonusPerLevel;
            
            $coinReward = round(($totalCoins * $finalPercent) / 100, 2);
            $totalDistributed += $coinReward;

            if ($coinReward > 0) {
                // Record via Ledger (updates wallet + logs transaction)
                $ledger->record(
                    $referrerId, 
                    'credit', 
                    $coinReward, 
                    'REFC-' . $userId . '-L' . ($index + 1), 
                    "Coin Reward Level " . ($index + 1) . " from User {$userId}",
                    'coins'
                );

                // Notify referrer
                $this->notificationModel->notify(
                    $referrerId,
                    "Referral Coin Reward!",
                    "You received {$coinReward} coins from a level " . ($index + 1) . " referral transaction.",
                    'reward',
                    'bi-coin'
                );
            }
        }

        // 4. Remainder to Admin
        $remainder = round($totalCoins - $totalDistributed, 2);
        if ($remainder > 0) {
            $admin = $this->userModel->where('is_admin', 1)->orderBy('id', 'ASC')->first();
            if ($admin) {
                $ledger->record(
                    $admin['id'], 
                    'credit', 
                    $remainder, 
                    'REFC-' . $userId . '-REM', 
                    "Coin Remainder from User {$userId}",
                    'coins'
                );
            }
        }

        $db->transComplete();
    }

    /**
     * Process rewards specifically for B2C services (Phase 9)
     */
    public function processServiceReward(int $userId, float $coinsEarned)
    {
        // For Phase 9, this simply wraps the MLM distribution logic
        $this->distributeCoinRewards($userId, $coinsEarned);
    }
}
