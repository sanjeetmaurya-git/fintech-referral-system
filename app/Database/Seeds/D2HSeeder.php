<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class D2HSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'         => 'Tata Play',
                'service_type' => 'd2h',
                'logo_url'     => 'assets/images/services/tataplay.png',
                'tier_1_max'   => 250,
                'tier_1_coins' => 5,
                'tier_2_max'   => 500,
                'tier_2_coins' => 12,
                'tier_3_max'   => 999999,
                'tier_3_coins' => 25,
                'is_active'    => 1
            ],
            [
                'name'         => 'Airtel Digital TV',
                'service_type' => 'd2h',
                'logo_url'     => 'assets/images/services/airteltv.png',
                'tier_1_max'   => 250,
                'tier_1_coins' => 5,
                'tier_2_max'   => 500,
                'tier_2_coins' => 12,
                'tier_3_max'   => 999999,
                'tier_3_coins' => 25,
                'is_active'    => 1
            ],
            [
                'name'         => 'Dish TV',
                'service_type' => 'd2h',
                'logo_url'     => 'assets/images/services/dishtv.png',
                'tier_1_max'   => 250,
                'tier_1_coins' => 5,
                'tier_2_max'   => 500,
                'tier_2_coins' => 12,
                'tier_3_max'   => 999999,
                'tier_3_coins' => 25,
                'is_active'    => 1
            ]
        ];

        foreach ($data as $op) {
            $this->db->table('service_recharge_operators')
                     ->where('name', $op['name'])
                     ->get()->getRow() 
                     ? null 
                     : $this->db->table('service_recharge_operators')->insert($op);
        }
    }
}
