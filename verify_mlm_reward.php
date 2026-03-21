<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'development');
CodeIgniter\Boot::bootConsole($paths);

use App\Models\UserModel;
use App\Models\WalletModel;
use App\Services\ReferralService;

$phone = '8874599237';
$amount = 1000.00;

echo "Simulating ₹$amount transaction for $phone...\n";

$userModel = new UserModel();
$user = $userModel->where('phone', $phone)->first();

if (!$user) die("User not found.\n");

// Check referral chain
$chain = [];
$currentId = $user['id'];
for ($i=0; $i<8; $i++) {
    $u = $userModel->find($currentId);
    if (!$u || empty($u['referred_by'])) break;
    $chain[] = $u['referred_by'];
    $currentId = $u['referred_by'];
}
echo "Referral Chain: " . implode(' -> ', $chain) . "\n";

// Record initial coins for the chain
$walletModel = new WalletModel();
$initialCoins = [];
foreach ($chain as $uid) {
    $w = $walletModel->where('user_id', $uid)->first();
    $initialCoins[$uid] = $w['coins'] ?? 0;
}

// Trigger reward processing
$refService = new ReferralService();
$refService->processTransactionRewards($user['id'], $amount);

echo "Reward processing completed.\n";

// Verify distribution
$tierModel = new \App\Models\TransactionRewardTierModel();
$tier = $tierModel->where('min_amount <=', $amount)->where('max_amount >=', $amount)->first();
$totalCoinsToDistribute = $tier['reward_coins'] ?? 0;
echo "Applicable Tier Reward: $totalCoinsToDistribute Coins\n";

echo "\nRESULTS:\n";
foreach ($chain as $index => $uid) {
    $w = $walletModel->where('user_id', $uid)->first();
    $gained = ($w['coins'] ?? 0) - $initialCoins[$uid];
    echo "Level " . ($index+1) . " (User ID $uid): Gained $gained Coins (Total: {$w['coins']})\n";
}
