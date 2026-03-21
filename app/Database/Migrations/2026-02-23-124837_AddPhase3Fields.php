<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhase3Fields extends Migration
{
    public function up()
    {
        // 1. Add is_premium to users
        $this->forge->addColumn('users', [
            'is_premium' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'is_active'],
        ]);

        // 2. Add Phase 3 Settings
        $db = \Config\Database::connect();
        $db->table('settings')->insertBatch([
            [
                'key'         => 'reward_mode',
                'value'       => 'coins',
                'description' => 'Mode for direct rewards (coins or cash)',
            ],
            [
                'key'         => 'premium_price_wallet',
                'value'       => '200.00',
                'description' => 'Price to upgrade to Premium (in ₹)',
            ],
            [
                'key'         => 'premium_price_coins',
                'value'       => '1000.00',
                'description' => 'Price to upgrade to Premium (in coins)',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_premium');
        $db = \Config\Database::connect();
        $db->table('settings')->whereIn('key', ['reward_mode', 'premium_price_wallet', 'premium_price_coins'])->delete();
    }
}
