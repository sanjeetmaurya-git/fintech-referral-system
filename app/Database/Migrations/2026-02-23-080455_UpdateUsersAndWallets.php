<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersAndWallets extends Migration
{
    public function up()
    {
        // Add columns to Users
        $this->forge->addColumn('users', [
            'password'      => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'phone'],
            'last_login_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'is_active'],
        ]);

        // Add columns to Wallets
        $this->forge->addColumn('wallets', [
            'coins' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00, 'after' => 'balance'],
        ]);

        // Update Settings for Phase 2
        $db = \Config\Database::connect();
        $db->table('settings')->where('key', 'base_reward_amount')->update(['value' => '100.00']);
        
        $db->table('settings')->insertBatch([
            ['key' => 'coin_rate',         'value' => '5',      'group' => 'rewards', 'description' => 'Number of coins per 1 Rupee'],
            ['key' => 'min_redemption',    'value' => '20',     'group' => 'rewards', 'description' => 'Minimum coins required to redeem'],
            ['key' => 'first_tx_coins',    'value' => '500',    'group' => 'rewards', 'description' => 'Coins awarded up the pyramid on first transaction'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['password', 'last_login_at']);
        $this->forge->dropColumn('wallets', ['coins']);
        
        $db = \Config\Database::connect();
        $db->table('settings')->whereIn('key', ['coin_rate', 'min_redemption', 'first_tx_coins'])->delete();
        $db->table('settings')->where('key', 'base_reward_amount')->update(['value' => '10.00']);
    }
}
