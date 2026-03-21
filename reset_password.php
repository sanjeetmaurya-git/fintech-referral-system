<?php
/**
 * Standalone Password Reset Script for CLI
 * Usage: php reset_password.php <phone_number> <new_password>
 */

// Define FCPATH and set path to public
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);

// Load CodeIgniter Bootstrap
require 'vendor/autoload.php';
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

// Initialize the application
$app = \Config\Services::codeigniter();
$app->initialize();

// Ensure it's CLI
if (php_sapi_name() !== 'cli') {
    die("Security Error: This script can only be run from the terminal.\n");
}

// Get Arguments
if ($argc < 3) {
    echo "Usage: php reset_password.php <phone_number> <new_password>\n";
    echo "Example: php reset_password.php 8874599237 NewPass123\n";
    exit(1);
}

$phone   = $argv[1];
$newPass = $argv[2];

echo "Attempting to reset password for User: $phone...\n";

$userModel = new \App\Models\UserModel();
$user = $userModel->where('phone', $phone)->first();

if (!$user) {
    echo "Error: User with phone number '$phone' not found in database.\n";
    exit(1);
}

$hashedPassword = password_hash($newPass, PASSWORD_DEFAULT);

if ($userModel->update($user['id'], ['password' => $hashedPassword])) {
    echo "Success! Password for user $phone (ID: {$user['id']}) has been updated.\n";
} else {
    echo "Error: Failed to update password in database.\n";
    exit(1);
}
