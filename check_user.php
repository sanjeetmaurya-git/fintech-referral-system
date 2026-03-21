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

$phone = '8874599237';
$userModel = new UserModel();
$walletModel = new WalletModel();

$user = $userModel->where('phone', $phone)->first();
if ($user) {
    echo "USER FOUND:\n";
    print_r($user);
    $wallet = $walletModel->where('user_id', $user['id'])->first();
    echo "\nWALLET FOUND:\n";
    print_r($wallet);
} else {
    echo "USER $phone NOT FOUND.\n";
}
