<?php

// CLI check
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the CLI.\n");
}

// Bootstrap CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';

// Define ENVIRONMENT if not set
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development'); 
}

CodeIgniter\Boot::bootConsole($paths);

use App\Models\UserModel;
use App\Models\WalletTransactionModel;
use App\Services\LedgerService;

// Arguments
if ($argc < 3) {
    echo "Usage: php generate_fake_transactions.php <phone> <count> [type:credit|debit]\n";
    echo "Example: php generate_fake_transactions.php 8874599237 10 debit\n";
    exit(1);
}

$phone = $argv[1];
$count = (int)$argv[2];
$type  = $argv[3] ?? 'credit';

echo "Generating $count $type transactions for user $phone...\n";

$userModel = new UserModel();
$user = $userModel->where('phone', $phone)->first();

if (!$user) {
    die("Error: User with phone $phone not found.\n");
}

$ledger = new LedgerService();
$transactionModel = new WalletTransactionModel();

$descriptions = [
    'credit' => [
        'Referral reward Level 1',
        'Bonus for active participation',
        'Reward points converted',
        'Welcome bonus',
        'Promotional credit'
    ],
    'debit' => [
        'Withdrawal request processed',
        'Premium upgrade fee',
        'Service charges',
        'Penalty for late action',
        'Transaction fee'
    ]
];

for ($i = 0; $i < $count; $i++) {
    $amount = rand(10, 500);
    $desc = $descriptions[$type][array_rand($descriptions[$type])];
    $ref = strtoupper($type === 'credit' ? 'FAKE-CR-' : 'FAKE-DR-') . bin2hex(random_bytes(4));

    echo "[$i] Creating $type of ₹$amount ($ref)...\n";

    // 1. Record in Ledger
    $ledger->record($user['id'], $type, $amount, $ref, $desc);

    // 2. Log in Transaction Table
    $transactionModel->insert([
        'user_id'      => $user['id'],
        'type'         => $type,
        'amount'       => $amount,
        'reference_id' => $ref,
        'description'  => $desc . " (Generated)",
        'status'       => 'approved',
    ]);
}

echo "Done! Generated $count transactions for UID {$user['id']}.\n";
