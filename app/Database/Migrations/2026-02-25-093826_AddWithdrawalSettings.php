<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWithdrawalSettings extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('settings');

        $settings = [
            [
                'key'         => 'withdrawal_min_days',
                'value'       => '3',
                'group'       => 'security',
                'description' => 'Minimum days after registration before first withdrawal.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'key'         => 'withdrawal_min_referral_coins',
                'value'       => '0',
                'group'       => 'security',
                'description' => 'Minimum referral coins earned to unlock withdrawals.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'key'         => 'withdrawal_premium_required',
                'value'       => '1',
                'group'       => 'security',
                'description' => 'Whether premium membership is required to withdraw referral rewards (1=Yes, 0=No).',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($settings as $s) {
            // Check if exists first to avoid duplicates if migration is rerun/fixed
            $check = $builder->where('key', $s['key'])->get()->getRow();
            if (!$check) {
                $builder->insert($s);
            }
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('settings');
        $builder->whereIn('key', ['withdrawal_min_days', 'withdrawal_min_referral_coins', 'withdrawal_premium_required'])->delete();
    }
}
