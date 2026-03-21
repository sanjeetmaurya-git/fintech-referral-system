<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Check if admin already exists
        $existing = $db->table('users')->where('phone', '9999999999')->get()->getRowArray();
        if ($existing) {
            echo "Admin account already exists (Phone: 9999999999).\n";
            return;
        }

        // Create wallet first (we need user ID)
        $userId = $db->table('users')->insert([
            'phone'         => '9999999999',
            'referral_code' => 'ADMINREF001',
            'is_active'     => 1,
            'is_admin'      => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        $userId = $db->insertID();

        // Create a wallet entry for the admin
        $db->table('wallets')->insert([
            'user_id'    => $userId,
            'balance'    => 0.00,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        echo "✅ Admin account created successfully!\n";
        echo "   Phone:    9999999999\n";
        echo "   Password: Login via OTP (check writable/logs/otp.log)\n";
        echo "   Admin ID: {$userId}\n";
    }
}
