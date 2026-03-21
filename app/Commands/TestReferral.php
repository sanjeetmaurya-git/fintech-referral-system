<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestReferral extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'app:test-referral';
    protected $description = 'Verifies the 8-level referral reward distribution.';

    public function run(array $params)
    {
        $userModel = new \App\Models\UserModel();
        $walletModel = new \App\Models\WalletModel();
        $referralService = new \App\Services\ReferralService();
        $referralLogModel = new \App\Models\ReferralLogModel();
        $transactionModel = new \App\Models\WalletTransactionModel();

        CLI::write("--- Cleaning up previous test data ---", 'yellow');
        $db = \Config\Database::connect();
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $db->table('users')->truncate();
        $db->table('wallets')->truncate();
        $db->table('wallet_transactions')->truncate();
        $db->table('referral_logs')->truncate();
        $db->table('otps')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        CLI::write("--- Starting Referral Logic Verification ---", 'yellow');

        $phones = [
            '9990000001', '9990000002', '9990000003', '9990000004', 
            '9990000005', '9990000006', '9990000007', '9990000008', '9990000009'
        ];

        $userIds = [];
        $referralCodes = [];

        foreach ($phones as $index => $phone) {
            CLI::print("Registering user: $phone... ");
            
            $referredByCode = $index > 0 ? $referralCodes[$index - 1] : null;
            $referralCode = 'REF' . $phone;
            
            // Check if exists
            $user = $userModel->where('phone', $phone)->first();
            if ($user) {
                $userId = $user['id'];
                $referralCode = $user['referral_code'];
                CLI::write("EXISTS (ID: $userId)", 'cyan');
            } else {
                $userId = $userModel->insert([
                    'phone' => $phone,
                    'referral_code' => $referralCode,
                    'is_active' => 1
                ]);
                CLI::write("SUCCESS (ID: $userId)", 'green');
            }
            
            $userIds[$index] = $userId;
            $referralCodes[$index] = $referralCode;

            if ($referredByCode) {
                $referralService->processReferral($userId, $referredByCode, '127.0.0.1');
            }
        }

        CLI::write("\n--- Verifying Wallet Balances (Phase 6: Should be 0.00 as pending) ---", 'yellow');

        $wallet = $walletModel->where('user_id', $userIds[0])->first();
        $balance = $wallet ? (float)$wallet['balance'] : 0.00;

        CLI::write("User 1 (Top Level) Balance: " . number_format($balance, 2), 'cyan');

        if (abs($balance - 0.00) < 0.01) {
            CLI::write("✅ TEST PASSED: Rewards are NOT credited instantly (Pending Status).", 'green');
        } else {
            CLI::error("❌ TEST FAILED: Rewards were credited instantly ($balance).");
        }

        // --- PHASE 6: FRAUD DETECTION TEST ---
        CLI::write("\n--- Testing Phase 6: Fraud Prevention (Duplicate Device) ---", 'yellow');
        
        $fraudPhone = '9991118881';
        $referralCodeForFraud = $referralCodes[0]; // User 1 refers
        $duplicateDeviceId = 'DEVICE-UNIQUE-ID-123';

        // Register first user with device
        $userModel->insert([
            'phone' => '9992223331',
            'referral_code' => 'REFFRAUD1',
            'device_id' => $duplicateDeviceId,
            'is_active' => 1
        ]);
        $firstUserId = $userModel->getInsertID();

        // Register second user with same device
        $secondUserId = $userModel->insert([
            'phone' => $fraudPhone,
            'referral_code' => 'REFFRAUD2',
            'device_id' => $duplicateDeviceId,
            'is_active' => 1
        ]);

        CLI::print("Attempting duplicate device registration: $fraudPhone... ");
        $referralService->processReferral($secondUserId, $referralCodeForFraud, '127.0.0.1');

        // Check if referral_by was set
        $secondUser = $userModel->find($secondUserId);
        $referralLog = (new \App\Models\ReferralLogModel())
            ->where('referred_user_id', $secondUserId)
            ->first();

        if (empty($secondUser['referred_by']) && !empty($referralLog['description']) && str_contains($referralLog['description'], 'Fraud')) {
            CLI::write("✅ SUCCESS: Duplicate device fraud blocked.", 'green');
            CLI::write("Log Detail: " . $referralLog['description'], 'cyan');
        } else {
            CLI::error("❌ FAILED: Duplicate device reward was not blocked.");
        }

        CLI::write("\n--- Verification Complete ---", 'yellow');
    }
}
