<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPremiumPriceSettings extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('settings');

        $settings = [
            [
                'key'         => 'premium_price_wallet',
                'value'       => '200',
                'group'       => 'general',
                'description' => 'Price for Premium Upgrade using Wallet Balance (₹)'
            ],
            [
                'key'         => 'premium_price_coins',
                'value'       => '1000',
                'group'       => 'general',
                'description' => 'Price for Premium Upgrade using Coins'
            ],
        ];

        foreach ($settings as $setting) {
            // Check if exists
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
        $builder->whereIn('key', ['premium_price_wallet', 'premium_price_coins'])->delete();
    }
}
