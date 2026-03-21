<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'development');
CodeIgniter\Boot::bootConsole($paths);

use App\Models\UserModel;
use App\Models\MembershipOrderModel;
use App\Controllers\AdminController;

$phone = '8874599237';
$amount = 500.00;

echo "Simulating Membership Approval for $phone (₹$amount)...\n";

$userModel = new UserModel();
$orderModel = new MembershipOrderModel();
$user = $userModel->where('phone', $phone)->first();

if (!$user) die("User not found.\n");

// 1. Create a dummy pending order
$orderId = $orderModel->insert([
    'user_id' => $user['id'],
    'payment_type' => 'upi',
    'payment_details' => 'Test Transaction',
    'amount' => $amount,
    'status' => 'pending'
]);

echo "Order #$orderId created.\n";

// 2. Clear first_tx flag for testing if needed
// $userModel->update($user['id'], ['has_done_first_tx' => 0]);

// 3. Manually trigger the approval logic
$admin = new AdminController();
// Note: We need to mock the session if AdminController has auth filters, 
// but since we are calling the method directly, let's see.
// Actually, it's better to just instantiate ReferralService and call processTransactionRewards
// to avoid redirect/view issues in CLI.

$refService = new \App\Services\ReferralService();
$refService->processTransactionRewards($user['id'], $amount);

echo "Reward processing triggered.\n";

// 4. Verify results
$updatedUser = $userModel->find($user['id']);
$walletModel = new \App\Models\WalletModel();
$wallet = $walletModel->where('user_id', $user['id'])->first();

echo "\nRESULTS for $phone:\n";
echo "has_done_first_tx: " . $updatedUser['has_done_first_tx'] . "\n";
echo "Wallet Balance: " . $wallet['balance'] . "\n";
echo "Wallet Coins: " . $wallet['coins'] . "\n";

if ((float)$wallet['coins'] >= 500) {
    echo "SUCCESS: User received 1:1 coin reward!\n";

// --- TEST SECOND TRANSACTION ---
echo "\n--- SIMULATING SECOND TRANSACTION (₹500) ---\n";
$refService->processTransactionRewards($user['id'], $amount);

// Verify referrers get points
$referrerId = $user['referred_by'];
if ($referrerId) {
    $refWallet = $walletModel->where('user_id', $referrerId)->first();
    echo "Referrer ID $referrerId Coins: " . $refWallet['coins'] . "\n";
    if ((float)$refWallet['coins'] > 0) {
        echo "SUCCESS: Referrer received tiered MLM reward!\n";
    } else {
        echo "FAILURE: Referrer did not receive coins.\n";
    }
} else {
    echo "NO REFERRER for this user to test MLM.\n";
}

} else {
    echo "FAILURE: Coins not rewarded correctly.\n";
}
