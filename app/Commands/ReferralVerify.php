<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ReferralVerify extends BaseCommand
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
    protected $name = 'referral:verify';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Verifies the referral system logic including 95/5 split and multi-level rewards.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'referral:verify';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $userModel = new \App\Models\UserModel();
        $transactionModel = new \App\Models\WalletTransactionModel();
        $referralService = new \App\Services\ReferralService();

        CLI::write('--- Referral System Phase 2 Verification ---', 'yellow');

        // 1. Test No-Referral (95/5 Split with ₹100)
        CLI::write('Test 1: Registration without Referral (₹100 @ 95/5 Split)', 'cyan');
        $testPhone1 = '1111111111';
        $userModel->where('phone', $testPhone1)->delete(); 
        
        $userId1 = $userModel->insert([
            'phone' => $testPhone1,
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'referral_code' => 'TESTREF1',
            'is_active' => 1
        ]);

        $referralService->processReferral($userId1, null, '127.0.0.1');
        
        $transactions = $transactionModel->where('reference_id', "REF-{$userId1}-NOREF")->findAll();
        CLI::write("User {$userId1} (No Referral) - Transactions Logged: " . count($transactions));
        foreach ($transactions as $t) {
            CLI::write("- User ID: {$t['user_id']}, Amount: {$t['amount']}, Type: {$t['type']}, Desc: {$t['description']}");
        }

        // 2. Test Single Level Referral (₹100)
        CLI::write('Test 2: Single Level Referral (₹100)', 'cyan');
        $testPhone2 = '2222222222';
        $userModel->where('phone', $testPhone2)->delete(); 
        
        $userId2 = $userModel->insert([
            'phone' => $testPhone2,
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'referral_code' => 'TESTREF2',
            'is_active' => 1
        ]);

        $referralService->processReferral($userId2, 'TESTREF1', '127.0.0.1');
        
        $reward = $transactionModel->where(['user_id' => $userId1, 'reference_id' => "REF-{$userId2}-L1"])->first();
        if ($reward && (float)$reward['amount'] === 100.00) {
            CLI::write("Direct Referral Success: User {$userId1} received ₹100.00 (100% redistributed) from {$userId2}", 'green');
        } else {
            CLI::error("FAILED: Direct Referral reward mismatch. Found: " . ($reward['amount'] ?? 'null'));
        }

        // 3. Test 6-Hour Auto Approval & 500 Coin MLM
        CLI::write('Test 3: Auto-Approval & 500 Coin MLM Bonus', 'cyan');
        
        // Manually manipulate transaction time back 7 hours for User 2's reward
        $db = \Config\Database::connect();
        $tx2 = $transactionModel->where(['user_id' => $userId1, 'reference_id' => "REF-{$userId2}-L1"])->first();
        
        $db->table('wallet_transactions')->where('id', $tx2['id'])->update([
            'created_at' => date('Y-m-d H:i:s', strtotime('-7 hours'))
        ]);

        // Run auto-approval logic for User 1 (They are the one expecting the reward from User 2)
        $dashboard = new \App\Controllers\UserDashboardController();
        $reflector = new \ReflectionClass($dashboard);
        $method = $reflector->getMethod('autoApproveRewards');
        $method->setAccessible(true);
        $method->invoke($dashboard, $userId1);

        $approvedTx = $transactionModel->find($tx2['id']);
        $user1 = $userModel->find($userId1); // Referrer
        $user2 = $userModel->find($userId2); // Origin
        $wallet1 = (new \App\Models\WalletModel())->where('user_id', $userId1)->first();

        if ($approvedTx['status'] === 'approved' && $user2['has_done_first_tx'] == 1) {
            CLI::write("Auto-Approval Success: User 1 reward from User 2 approved.", 'green');
            CLI::write("First Transaction Flag set for User 2 (Origin)", 'green');
            CLI::write("MLM Coin Bonus Check for User 1 (Referrer): " . $wallet1['coins'], 'yellow');
            
            if ((float)$wallet1['coins'] > 0) {
                CLI::write("Coin Reward Success!", 'green');
            }
        } else {
            CLI::error("FAILED: Auto-approval or First-TX flag logic. TX Status: " . $approvedTx['status'] . ", User2 Flag: " . ($user2['has_done_first_tx'] ?? 'N/A'));
        }

        // 4. Test Dynamic Redistribution (Chain of 2)
        CLI::write('Test 4: Dynamic Redistribution (2 Levels for ₹100 Pool)', 'cyan');
        $testPhone3 = '3333333333';
        $testPhone4 = '4444444444';
        
        $userModel->whereIn('phone', [$testPhone3, $testPhone4])->delete();

        $userId3 = $userModel->insert(['phone' => $testPhone3, 'password' => password_hash('pass', PASSWORD_DEFAULT), 'referral_code' => 'T3', 'is_active' => 1]);
        $userId4 = $userModel->insert(['phone' => $testPhone4, 'password' => password_hash('pass', PASSWORD_DEFAULT), 'referral_code' => 'T4', 'is_active' => 1, 'referred_by' => $userId3]);
        
        $testPhone5 = '5555555555';
        $userModel->where('phone', $testPhone5)->delete();
        $userId5 = $userModel->insert(['phone' => $testPhone5, 'password' => password_hash('pass', PASSWORD_DEFAULT), 'referral_code' => 'T5', 'is_active' => 1, 'referred_by' => $userId4]);

        // Process referral for User 5 (Chain: 5 -> 4 -> 3)
        $referralService->processReferral($userId5, 'T4', '127.0.0.1');

        $tx4 = $transactionModel->where(['user_id' => $userId4, 'reference_id' => "REF-{$userId5}-L1"])->first();
        $tx3 = $transactionModel->where(['user_id' => $userId3, 'reference_id' => "REF-{$userId5}-L2"])->first();

        CLI::write("L1 (User 4) Reward: ₹" . ($tx4['amount'] ?? 0));
        CLI::write("L2 (User 3) Reward: ₹" . ($tx3['amount'] ?? 0));

        $totalRecieved = (float)($tx4['amount'] ?? 0) + (float)($tx3['amount'] ?? 0);
        if ($totalRecieved >= 99.99) { // Allow for small rounding if any
            CLI::write("Redistribution Success: Full pool distributed over 2 levels.", 'green');
        } else {
            CLI::error("FAILED: Redistribution did not cover full pool. Total: ₹{$totalRecieved}");
        }

        CLI::write('--- Verification Completed ---', 'yellow');
    }
}
