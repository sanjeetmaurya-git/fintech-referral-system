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
use App\Services\LedgerService;

$senderPhone = '8874599237';
$receiverPhone = '9990000001';
$amount = 500.00;

echo "Simulating transfer of ₹$amount from $senderPhone to $receiverPhone...\n";

$userModel = new UserModel();
$sender = $userModel->where('phone', $senderPhone)->first();
$receiver = $userModel->where('phone', $receiverPhone)->first();

if (!$sender) die("Error: Sender $senderPhone not found.\n");
if (!$receiver) die("Error: Receiver $receiverPhone not found.\n");

$ledger = new LedgerService();
$db = \Config\Database::connect();
$db->transStart();

// 1. Debit Sender
$ledger->record(
    $sender['id'], 
    'debit', 
    $amount, 
    'TEST-TRF-SEN-' . time(), 
    "Sent ₹$amount to $receiverPhone (Test Transaction)"
);

// 2. Credit Receiver
$ledger->record(
    $receiver['id'], 
    'credit', 
    $amount, 
    'TEST-TRF-REC-' . time(), 
    "Received ₹$amount from $senderPhone (Test Transaction)"
);

$db->transComplete();

if ($db->transStatus() === false) {
    die("Error: Transaction failed.\n");
}

echo "Success! Transferred ₹$amount between users.\n";
echo "Sender ({$senderPhone}) Balance Updated.\n";
echo "Receiver ({$receiverPhone}) Balance Updated.\n";
