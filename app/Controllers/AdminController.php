<?php

namespace App\Controllers;

use App\Models\MembershipOrderModel;
use App\Models\SettingModel;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\ReferralLogModel;
use App\Models\WithdrawalModel;
use App\Services\LedgerService;
use CodeIgniter\Controller;

use App\Models\AuditLogModel;
use App\Models\TransactionRewardTierModel;
use App\Models\ServiceRechargeOperatorModel;
use App\Models\ServiceEcommercePlatformModel;
use App\Models\ServiceTransactionModel;
use App\Models\WorkerModel;
use App\Models\WorkerDocumentModel;
use App\Models\WorkCategoryModel;
use App\Models\WorkSubcategoryModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $walletModel;
    protected $transactionModel;
    protected $referralLogModel;
    protected $orderModel;
    protected $tierModel;
    protected $auditLogModel;
    protected $rechargeOperatorModel;
    protected $ecommercePlatformModel;
    protected $serviceTransactionModel;

    public function __construct()
    {
        $this->userModel        = new UserModel();
        $this->walletModel      = new WalletModel();
        $this->transactionModel = new WalletTransactionModel();
        $this->referralLogModel = new ReferralLogModel();
        $this->orderModel       = new MembershipOrderModel();
        $this->tierModel        = new TransactionRewardTierModel();
        $this->auditLogModel    = new AuditLogModel();
        $this->rechargeOperatorModel   = new ServiceRechargeOperatorModel();
        $this->ecommercePlatformModel  = new ServiceEcommercePlatformModel();
        $this->serviceTransactionModel = new ServiceTransactionModel();
        $this->workerModel            = new WorkerModel();
        $this->workerDocModel        = new WorkerDocumentModel();
        $this->workCategoryModel     = new WorkCategoryModel();
    }

    public function index()
    {
        $withdrawalModel = new WithdrawalModel();

        // 1. Core Analytics
        $totalUsers       = $this->userModel->countAllResults();
        $totalWithdrawals = $withdrawalModel->where('status', 'completed')->selectSum('amount')->first()['amount'] ?? 0;
        $pendingPayouts   = $withdrawalModel->where('status', 'pending')->selectSum('amount')->first()['amount'] ?? 0;
        $totalRewards     = $this->transactionModel->where('status', 'approved')->selectSum('amount')->first()['amount'] ?? 0;
        $total_Premium   = $this->userModel->where('is_premium', 1)->countAllResults();       //Total Premium Users 

        
        // Phase 8: Revenue & Coins
        $totalRevenue     = $this->orderModel->where('status', 'approved')->selectSum('amount')->first()['amount'] ?? 0;
        $totalCoins       = $this->walletModel->selectSum('coins')->first()['coins'] ?? 0;

        // 2. Trend Data (Last 7 Days)
        $registrationTrend = [];
        $payoutTrend = [];
        $revenueTrend = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            
            $regCount = $this->userModel->where("DATE(created_at)", $date)->countAllResults();
            $paySum = $withdrawalModel->selectSum('amount')
                                      ->where('status', 'completed')
                                      ->where("DATE(updated_at)", $date) // Use updated_at for when it was actually processed
                                      ->first()['amount'] ?? 0;
                                      
            $revSum = $this->orderModel->selectSum('amount')
                                       ->where('status', 'approved')
                                       ->where("DATE(updated_at)", $date)
                                       ->first()['amount'] ?? 0;

            $registrationTrend[$date] = $regCount;
            $payoutTrend[$date] = (float)$paySum;
            $revenueTrend[$date] = (float)$revSum;
        }

        // 3. Referral Level Density
        $levelDensity = [];
        for ($l = 1; $l <= 8; $l++) {
            $count = $this->transactionModel->where('status', 'approved')
                                            ->where("reference_id LIKE", "%-L{$l}%")
                                            ->countAllResults();
            $levelDensity["Level {$l}"] = $count;
        }

        // 4. Fraud Summary
        $fraudLogModel = new ReferralLogModel();
        $fraudAttempts = $fraudLogModel->where("description LIKE", "FRAUD_%")->orderBy('id', 'DESC')->limit(10)->findAll();

        $data = [
            'title'             => 'Admin Intelligence',
            'active'            => 'dashboard',
            'stats'             => [
                'total_users'       => $totalUsers,
                'total_withdrawals' => $totalWithdrawals,
                'pending_payouts'   => $pendingPayouts,
                'total_rewards'     => $totalRewards,
                'total_revenue'     => $totalRevenue,
                'total_coins'       => $totalCoins,
                'total_premium'     => $total_Premium,  
            ],
            'charts' => [
                'registration' => $registrationTrend,
                'payout'       => $payoutTrend,
                'revenue'      => $revenueTrend,
                'distribution' => $levelDensity
            ],
            'top_referrers'     => $this->userModel->select('users.id, users.phone, COUNT(r.id) as referral_count, SUM(r.is_premium) as premium_count')
                                               ->join('users r', 'r.referred_by = users.id', 'left')
                                               ->groupBy('users.id')
                                               ->orderBy('premium_count', 'DESC')
                                               ->limit(5)
                                               ->findAll(),
            'fraud_attempts'    => $fraudAttempts,
            'audit_logs'        => $this->auditLogModel->orderBy('id', 'DESC')->limit(10)->findAll()
        ];

        return view('admin/dashboard', $data);
    }

    public function users()
    {
        $data = [
            'title'  => 'Users',
            'active' => 'users',
            'users'  => $this->userModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/users', $data);
    }

    public function transactions()
    {
        $status = $this->request->getGet('status') ?? 'all';
        
        $query = $this->transactionModel;
        if ($status !== 'all') {
            $query = $query->where('status', $status);
        }

        $data = [
            'title'          => 'Transactions',
            'active'         => 'transactions',
            'transactions'   => $query->orderBy('id', 'DESC')->findAll(),
            'current_status' => $status,
        ];

        return view('admin/transactions', $data);
    }

    public function approveReward(int $id)
    {
        $transaction = $this->transactionModel->find($id);

        if (!$transaction || $transaction['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid transaction or already processed.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Update Transaction Status
        $this->transactionModel->update($id, ['status' => 'approved']);

        // 2. Determine Account (Balance vs Coins)
        $account = 'balance';
        if (strpos($transaction['reference_id'], 'REFC-') === 0) {
            $account = 'coins';
        }

        // 3. Update via Ledger System
        $ledger = new LedgerService();
        $ledger->record(
            $transaction['user_id'], 
            'credit', 
            (float)$transaction['amount'], 
            $transaction['reference_id'], 
            'Referral Reward Approved',
            $account
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Transaction failed.');
        }

        // Notify User (Phase 8)
        $rewardService = new \App\Services\RewardService();
        $rewardService->notifyRewardApproved($transaction['user_id'], (float)$transaction['amount']);

        // Audit Log
        $this->auditLogModel->log(session()->get('user_id'), 'APPROVE_REWARD', "Approved reward transaction ID: {$id}");

        return redirect()->back()->with('success', 'Reward approved successfully.');
    }

    public function rejectReward(int $id)
    {
        $transaction = $this->transactionModel->find($id);

        if (!$transaction || $transaction['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid transaction or already processed.');
        }

        $this->transactionModel->update($id, ['status' => 'rejected']);

        // Notify User (Phase 8)
        $rewardService = new \App\Services\RewardService();
        $rewardService->notifyRewardRejected($transaction['user_id'], (float)$transaction['amount']);

        // Audit Log
        $this->auditLogModel->log(session()->get('user_id'), 'REJECT_REWARD', "Rejected reward transaction ID: {$id}");

        return redirect()->back()->with('success', 'Reward rejected.');
    }

    // ==========================
    // Phase 9: Withdrawals
    // ==========================

    public function withdrawals()
    {
        $withdrawalModel = new \App\Models\WithdrawalModel();
        $data = [
            'title'          => 'Withdrawals',
            'active'         => 'withdrawals',
            'withdrawals'    => $withdrawalModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/withdrawals', $data);
    }

    public function approveWithdrawal(int $id)
    {
        $withdrawalModel = new \App\Models\WithdrawalModel();
        $withdrawal = $withdrawalModel->find($id);

        if (!$withdrawal || $withdrawal['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid withdrawal or already processed.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Deduct from wallet via Ledger
        $wallet = $this->walletModel->where('user_id', $withdrawal['user_id'])->first();
        if ($wallet['balance'] < $withdrawal['amount']) {
            return redirect()->back()->with('error', 'User has insufficient balance now.');
        }

        $ledger = new LedgerService();
        $success = $ledger->record(
            $withdrawal['user_id'],
            'debit',
            (float)$withdrawal['amount'],
            'WD-' . $id,
            'Withdrawal Payout Approved'
        );

        if (!$success) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Ledger recording failed.');
        }

        // 2. Mark Withdrawal as completed
        $withdrawalModel->update($id, ['status' => 'completed']);

        // 3. Log as a transaction record
        $this->transactionModel->insert([
            'user_id'      => $withdrawal['user_id'],
            'type'         => 'debit',
            'amount'       => $withdrawal['amount'],
            'reference_id' => 'WD-' . $id,
            'description'  => 'Withdrawal Payout',
            'status'       => 'approved',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to approve withdrawal.');
        }

        // Audit Log
        $this->auditLogModel->log(session()->get('user_id'), 'APPROVE_WITHDRAWAL', "Approved withdrawal ID: {$id}");

        return redirect()->back()->with('success', 'Withdrawal approved and balance deducted.');
    }

    public function rejectWithdrawal(int $id)
    {
        $withdrawalModel = new \App\Models\WithdrawalModel();
        $withdrawal = $withdrawalModel->find($id);

        if (!$withdrawal || $withdrawal['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid withdrawal or already processed.');
        }

        $withdrawalModel->update($id, ['status' => 'rejected']);

        // Audit Log
        $this->auditLogModel->log(session()->get('user_id'), 'REJECT_WITHDRAWAL', "Rejected withdrawal ID: {$id}");

        return redirect()->back()->with('success', 'Withdrawal request rejected.');
    }

    // ==================================
    // Phase 10: Dynamic settings
    // ==================================

    public function settings()
    {
        $settingModel = new SettingModel();
        $data = [
            'title'    => 'System Settings',
            'active'   => 'settings',
            'settings' => $settingModel->orderBy('group', 'ASC')->findAll(),
            'tiers'    => $this->tierModel->orderBy('min_amount', 'ASC')->findAll(),
        ];
        return view('admin/settings', $data);
    }

    public function updateSettings()
    {
        $settingModel = new SettingModel();
        $inputs = $this->request->getPost('settings');
        $tierInputs = $this->request->getPost('tiers');

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Save standard settings
        if ($inputs) {
            // Validate Reward Percentages (Sum <= 100)
            $currentSettings = $settingModel->where('group', 'rewards')->findAll();
            $rewardMap = [];
            foreach ($currentSettings as $s) {
                if (strpos($s['key'], 'reward_level_') !== false) {
                    $rewardMap[$s['key']] = (float)$s['value'];
                }
            }

            $hasRewardLevel = false;
            foreach ($inputs as $key => $value) {
                if (strpos($key, 'reward_level_') !== false) {
                    $rewardMap[$key] = (float)$value;
                    $hasRewardLevel = true;
                }
            }

            if ($hasRewardLevel) {
                $totalPercent = array_sum($rewardMap);
                if ($totalPercent > 100) {
                    return redirect()->back()->withInput()->with('error', "Validation Failed: The sum of all reward levels ({$totalPercent}%) exceeds 100%.");
                }
            }

            foreach ($inputs as $key => $value) {
                $settingModel->setVal($key, $value);
            }
        }

        // 2. Save Transaction Reward Tiers
        $this->tierModel->emptyTable(); // Simple approach: clear and re-insert
        if (!empty($tierInputs) && is_array($tierInputs)) {
            $count = 0;
            foreach ($tierInputs as $tier) {
                if ($count >= 8) break; // Limit to 8 as requested
                if (empty($tier['reward_coins'])) continue;
                
                $this->tierModel->insert([
                    'min_amount'   => (float)$tier['min'],
                    'max_amount'   => (float)$tier['max'],
                    'reward_coins' => (float)$tier['reward_coins']
                ]);
                $count++;
            }
        }

        $db->transComplete();

        // Audit Log
        $this->auditLogModel->log(session()->get('user_id'), 'UPDATE_SETTINGS', "Updated system settings.");

        return redirect()->to(base_url('admin/settings'))->with('success', 'System settings updated successfully.');
    }

    /**
     * Handle Batch Actions (Approve / Export)
     */
    public function processBatchAction()
    {
        $ids = $this->request->getPost('ids');
        $action = $this->request->getPost('action');

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        $withdrawalModel = new \App\Models\WithdrawalModel();

        if ($action === 'approve') {
            $successCount = 0;
            $ledger = new \App\Services\LedgerService();
            $db = \Config\Database::connect();

            foreach ($ids as $id) {
                $withdrawal = $withdrawalModel->find($id);
                if (!$withdrawal || $withdrawal['status'] !== 'pending') continue;

                $db->transStart();
                
                // Ledger Record (Debit)
                $success = $ledger->record(
                    $withdrawal['user_id'],
                    'debit',
                    (float)$withdrawal['amount'],
                    'WD-' . $id,
                    'Batch Payout Approved'
                );

                if ($success) {
                    $withdrawalModel->update($id, ['status' => 'completed']);
                    $successCount++;
                }
                
                $db->transComplete();
            }

            return redirect()->back()->with('success', "Batch processed: {$successCount} withdrawals approved.");
        }

        if ($action === 'export') {
            $withdrawals = $withdrawalModel->select('withdrawals.*, users.phone')
                                           ->join('users', 'users.id = withdrawals.user_id')
                                           ->whereIn('withdrawals.id', $ids)
                                           ->findAll();

            $filename = 'withdrawals_export_' . date('Ymd_His') . '.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Phone', 'Amount', 'Details', 'Status', 'Date']);

            foreach ($withdrawals as $w) {
                fputcsv($output, [
                    $w['id'],
                    $w['phone'],
                    $w['amount'],
                    str_replace(["\r", "\n"], ' ', $w['payment_details']),
                    $w['status'],
                    $w['created_at']
                ]);
            }
            fclose($output);
            exit;
        }
        return redirect()->back();
    }

    /**
     * View Premium Upgrade Orders
     */
    public function membershipOrders()
    {
        $data = [
            'title'  => 'Membership Orders',
            'active' => 'membership-orders',
            'orders' => $this->orderModel->getOrdersWithUser()
        ];
        return view('admin/membership_orders', $data);
    }

    /**
     * Approve Membership Order
     */
    public function approveMembershipOrder(int $id)
    {
        $order = $this->orderModel->find($id);
        if (!$order || $order['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid order or already processed.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Mark User as Premium
        $this->userModel->update($order['user_id'], ['is_premium' => 1]);

        // 2. Update Order Status
        $this->orderModel->update($id, ['status' => 'approved']);

        // 3. Log Transaction
        $this->transactionModel->insert([
            'user_id'      => $order['user_id'],
            'type'         => 'debit',
            'amount'       => $order['amount'],
            'reference_id' => 'PRM-ORDER-' . $id,
            'description'  => "Premium Upgrade Approved ({$order['payment_type']})",
            'status'       => 'approved',
        ]);

        // 4. Trigger Transaction-Based Rewards (First Tx: 1:1, Subsequent: Tiered MLM)
        $referralService = new \App\Services\ReferralService();
        $referralService->processTransactionRewards($order['user_id'], (float)$order['amount']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Approval failed.');
        }

        // Audit Log
        $this->auditLogModel->log(session()->get('user_id'), 'APPROVE_MEMBERSHIP', "Approved membership order ID: {$id} for User ID: " . $order['user_id']);

        return redirect()->back()->with('success', 'Membership order approved successfully.');
    }

    /**
     * Reject Membership Order
     */
    public function rejectMembershipOrder(int $id)
    {
        $order = $this->orderModel->find($id);
        if (!$order || $order['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid order.');
        }

        // If rejected, we might need to refund coins/balance? 
        // User request didn't specify, but usually we should refund if we deducted immediately.
        // However, the UserDashboardController update will change it to record the 'intent'.
        
        $this->orderModel->update($id, ['status' => 'rejected']);
        return redirect()->back()->with('success', 'Membership order rejected.');
    }

    /**
     * User Settings (Membership & Network View)
     */
    public function userSettings()
    {
        $users = $this->userModel->orderBy('id', 'DESC')->findAll();
        
        // Add network count to each user
        foreach ($users as &$user) {
            $user['network_count'] = $this->getLevel8NetworkCount($user['id']);
        }

        $data = [
            'title'  => 'User Settings',
            'active' => 'user-settings',
            'users'  => $users
        ];
        return view('admin/user_settings', $data);
    }

    /**
     * Direct Update User Premium Status
     */
    public function updateUserPremiumStatus()
    {
        $userId = $this->request->getPost('user_id');
        $status = $this->request->getPost('is_premium');

        if ($this->userModel->update($userId, ['is_premium' => $status])) {
            return redirect()->back()->with('success', 'User status updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update user status.');
    }

    /**
     * Calculated Level 8 Network Count
     */
    private function getLevel8NetworkCount($userId)
    {
        $totalCount = 0;
        $currentLevelIds = [$userId];

        for ($depth = 1; $depth <= 8; $depth++) {
            if (empty($currentLevelIds)) break;

            $nextLevelUsers = $this->userModel->whereIn('referred_by', $currentLevelIds)->findAll();
            $nextLevelIds = array_column($nextLevelUsers, 'id');
            
            $totalCount += count($nextLevelIds);
            $currentLevelIds = $nextLevelIds;
        }

        return $totalCount;
    }

    // ==================================
    // Phase 9: Service Management
    // ==================================

    public function manageServices()
    {
        $data = [
            'title'  => 'Services Management',
            'active' => 'services',
            'counts' => [
                'operators' => $this->rechargeOperatorModel->countAllResults(),
                'platforms' => $this->ecommercePlatformModel->countAllResults(),
                'pending'   => $this->serviceTransactionModel->where('status', 'pending')->countAllResults()
            ]
        ];
        return view('admin/services/index', $data);
    }

    public function manageRechargeOperators()
    {
        $data = [
            'title'     => 'Recharge Operators',
            'active'    => 'services',
            'operators' => $this->rechargeOperatorModel->findAll()
        ];
        return view('admin/services/recharge', $data);
    }

    public function saveRechargeOperator()
    {
        $id = $this->request->getPost('id');
        $allowedTypes = ['jpg', 'jpeg', 'webp', 'png', 'svg'];

        $data = [
            'name'         => $this->request->getPost('name'),
            'tier_1_max'   => $this->request->getPost('tier_1_max'),
            'tier_1_coins' => $this->request->getPost('tier_1_coins'),
            'tier_2_max'   => $this->request->getPost('tier_2_max'),
            'tier_2_coins' => $this->request->getPost('tier_2_coins'),
            'tier_3_max'   => $this->request->getPost('tier_3_max'),
            'tier_3_coins' => $this->request->getPost('tier_3_coins'),
            'tier_4_max'   => $this->request->getPost('tier_3_max'),  // mirror tier 3
            'tier_4_coins' => $this->request->getPost('tier_3_coins'), // mirror tier 3
            'is_active'    => $this->request->getPost('is_active') ?? 1
        ];

        // Handle logo file upload
        $logoFile = $this->request->getFile('logo_url');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $ext = strtolower($logoFile->getClientExtension());
            if (!in_array($ext, $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'Invalid file type. Allowed: JPG, JPEG, WEBP, PNG, SVG.');
            }
            if ($logoFile->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Image must be under 2MB.');
            }
            // Delete old logo
            $currentLogo = $this->request->getPost('current_logo');
            if ($currentLogo && file_exists(FCPATH . $currentLogo)) {
                @unlink(FCPATH . $currentLogo);
            }
            $newName = $logoFile->getRandomName();
            $logoFile->move(FCPATH . 'assets/images/services', $newName);
            $data['logo_url'] = 'assets/images/services/' . $newName;
        } else {
            // Keep existing logo
            $data['logo_url'] = $this->request->getPost('current_logo');
        }

        if ($id) {
            $this->rechargeOperatorModel->update($id, $data);
        } else {
            $this->rechargeOperatorModel->insert($data);
        }

        return redirect()->back()->with('success', 'Operator saved successfully.');
    }

    public function manageEcommercePlatforms()
    {
        $data = [
            'title'     => 'Ecommerce Platforms',
            'active'    => 'services',
            'platforms' => $this->ecommercePlatformModel->findAll()
        ];
        return view('admin/services/ecommerce', $data);
    }

    public function saveEcommercePlatform()
    {
        $id = $this->request->getPost('id');
        $allowedTypes = ['jpg', 'jpeg', 'webp', 'png', 'svg'];

        $data = [
            'name'          => $this->request->getPost('name'),
            'category'      => $this->request->getPost('category'),
            'affiliate_url' => $this->request->getPost('affiliate_url'),
            'tier_1_max'    => $this->request->getPost('tier_1_max'),
            'tier_1_coins'  => $this->request->getPost('tier_1_coins'),
            'tier_2_max'    => $this->request->getPost('tier_2_max'),
            'tier_2_coins'  => $this->request->getPost('tier_2_coins'),
            'tier_3_max'    => $this->request->getPost('tier_3_max'),
            'tier_3_coins'  => $this->request->getPost('tier_3_coins'),
            'is_active'     => $this->request->getPost('is_active') ?? 1
        ];

        // Handle logo file upload
        $logoFile = $this->request->getFile('logo_url');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $ext = strtolower($logoFile->getClientExtension());
            if (!in_array($ext, $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'Invalid file type. Allowed: JPG, JPEG, WEBP, PNG, SVG.');
            }
            if ($logoFile->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Image must be under 2MB.');
            }
            // Delete old logo
            $currentLogo = $this->request->getPost('current_logo');
            if ($currentLogo && file_exists(FCPATH . $currentLogo)) {
                @unlink(FCPATH . $currentLogo);
            }
            $newName = $logoFile->getRandomName();
            $logoFile->move(FCPATH . 'assets/images/services', $newName);
            $data['logo_url'] = 'assets/images/services/' . $newName;
        } else {
            // Keep existing logo
            $data['logo_url'] = $this->request->getPost('current_logo');
        }

        if ($id) {
            $this->ecommercePlatformModel->update($id, $data);
        } else {
            $this->ecommercePlatformModel->insert($data);
        }

        return redirect()->back()->with('success', 'Platform saved successfully.');
    }

    public function manageServiceTransactions()
    {
        $data = [
            'title'        => 'Service Transactions',
            'active'       => 'services',
            'transactions' => $this->serviceTransactionModel->getPending()
        ];
        return view('admin/services/transactions', $data);
    }

    public function approveServiceTransaction($id)
    {
        $transaction = $this->serviceTransactionModel->find($id);
        if (!$transaction || $transaction['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid transaction.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Mark as Approved
        $this->serviceTransactionModel->update($id, ['status' => 'approved']);

        // 2. Distribute Coin Rewards via ReferralService
        if ($transaction['coins_earned'] > 0) {
            $referralService = new \App\Services\ReferralService();
            $referralService->processServiceReward($transaction['user_id'], (float)$transaction['coins_earned']);
        }

        // 3. Log Audit
        $this->auditLogModel->log(session()->get('user_id'), 'APPROVE_SERVICE', "Approved service transaction #{$id} for User #{$transaction['user_id']}");

        $db->transComplete();

        return redirect()->back()->with('success', 'Service transaction approved and rewards distributed.');
    }

    // ==================================
    // Phase 10: Worker Management
    // ==================================

    public function workers()
    {
        $status = $this->request->getGet('status') ?? 'all';
        $query = $this->workerModel->select('workers.*, users.phone, work_categories.name as category_name')
                                   ->join('users', 'users.id = workers.user_id')
                                   ->join('work_categories', 'work_categories.id = workers.category_id', 'left');
        
        if ($status !== 'all') {
            $query = $query->where('workers.status', $status);
        }

        $data = [
            'title'          => 'Worker Management',
            'active'         => 'workers',
            'workers'        => $query->orderBy('workers.id', 'DESC')->findAll(),
            'current_status' => $status
        ];
        return view('admin/workers/index', $data);
    }

    public function viewWorker($id)
    {
        $worker = $this->workerModel->select('workers.*, users.phone, up.full_name, up.email, work_categories.name as category_name, work_subcategories.name as subcategory_name')
                                    ->join('users', 'users.id = workers.user_id')
                                    ->join('user_profiles up', 'up.user_id = users.id', 'left')
                                    ->join('work_categories', 'work_categories.id = workers.category_id', 'left')
                                    ->join('work_subcategories', 'work_subcategories.id = workers.subcategory_id', 'left')
                                    ->where('workers.id', $id)
                                    ->first();

        if (!$worker) {
            return redirect()->to(base_url('admin/workers'))->with('error', 'Worker not found.');
        }

        $data = [
            'title'     => 'Verify Worker: ' . ($worker['full_name'] ?? 'User'),
            'active'    => 'workers',
            'worker'    => $worker,
            'documents' => $this->workerDocModel->where('worker_id', $id)->findAll()
        ];
        return view('admin/workers/view', $data);
    }

    public function approveWorker($id)
    {
        if ($this->workerModel->update($id, ['status' => 'approved'])) {
            $this->auditLogModel->log(session()->get('user_id'), 'APPROVE_WORKER', "Approved worker application ID: {$id}");
            return redirect()->to(base_url('admin/workers'))->with('success', 'Worker application approved.');
        }
        return redirect()->back()->with('error', 'Failed to approve worker.');
    }

    public function rejectWorker($id)
    {
        if ($this->workerModel->update($id, ['status' => 'rejected'])) {
            $this->auditLogModel->log(session()->get('user_id'), 'REJECT_WORKER', "Rejected worker application ID: {$id}");
            return redirect()->to(base_url('admin/workers'))->with('success', 'Worker application rejected.');
        }
        return redirect()->back()->with('error', 'Failed to reject worker.');
    }

    public function verifyDocument($docId)
    {
        $docModel = new WorkerDocumentModel();
        $doc = $docModel->find($docId);

        if (!$doc) {
            return $this->response->setJSON(['success' => false, 'message' => 'Document not found.']);
        }

        $newStatus  = $doc['is_verified'] ? 0 : 1;
        $verifiedAt = $newStatus ? date('Y-m-d H:i:s') : null;

        $docModel->skipValidation(true)->update($docId, [
            'is_verified' => $newStatus,
            'verified_at' => $verifiedAt,
        ]);

        $this->auditLogModel->log(
            session()->get('user_id'),
            'VERIFY_DOCUMENT',
            "Document #{$docId} marked as " . ($newStatus ? 'verified' : 'unverified')
        );

        return $this->response->setJSON([
            'success'     => true,
            'is_verified' => (bool)$newStatus,
            'verified_at' => $verifiedAt,
        ]);
    }
}

