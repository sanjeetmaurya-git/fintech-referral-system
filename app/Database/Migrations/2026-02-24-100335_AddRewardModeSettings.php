<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRewardModeSettings extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('settings');

        $settings = [
            [
                'key'         => 'reward_mode',
                'value'       => 'balance', // 'balance' or 'coins'
                'group'       => 'rewards',
                'description' => 'Determines if referral rewards are given as Wallet Balance or Coins'
            ],
            [
                'key'         => 'coin_value',
                'value'       => '1.00',
                'group'       => 'general',
                'description' => 'The value of 1 Coin in ₹ (e.g., if set to 0.5, then 1 Coin = ₹0.5)'
            ],
        ];

        foreach ($settings as $setting) {
            $exists = $builder->where('key', $setting['key'])->countAllResults();
            if (!$exists) {
                $builder->insert($setting);
            }
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('settings');
        $builder->whereIn('key', ['reward_mode', 'coin_value'])->delete();
    }
}
