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

$phones = ['9990000001', '8874599237'];
$userModel = new UserModel();
$walletModel = new WalletModel();

foreach ($phones as $phone) {
    echo "\n--- STATUS FOR $phone ---\n";
    $user = $userModel->where('phone', $phone)->first();
    if ($user) {
        print_r([
            'id' => $user['id'],
            'phone' => $user['phone'],
            'referred_by' => $user['referred_by'],
            'has_done_first_tx' => $user['has_done_first_tx'],
            'is_premium' => $user['is_premium']
        ]);
        $wallet = $walletModel->where('user_id', $user['id'])->first();
        echo "Wallet Balance: " . ($wallet['balance'] ?? 0) . "\n";
        echo "Wallet Coins: " . ($wallet['coins'] ?? 0) . "\n";
    } else {
        echo "USER NOT FOUND.\n";
    }
}
