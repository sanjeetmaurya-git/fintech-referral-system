<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        // 1. Recharge Operators
        $operators = [
            [
                'name'         => 'Jio',
                'logo_url'     => 'images/operators/jio_logo.svg',
                'tier_1_max'   => 199,
                'tier_1_coins' => 2,
                'tier_2_max'   => 499,
                'tier_2_coins' => 5,
                'tier_3_max'   => 999,
                'tier_3_coins' => 12,
                'tier_4_max'   => 9999,
                'tier_4_coins' => 25,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Airtel',
                'logo_url'     => 'images/operators/airtel_logo.svg',
                'tier_1_max'   => 199,
                'tier_1_coins' => 2,
                'tier_2_max'   => 499,
                'tier_2_coins' => 5,
                'tier_3_max'   => 999,
                'tier_3_coins' => 12,
                'tier_4_max'   => 9999,
                'tier_4_coins' => 25,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'VI',
                'logo_url'     => 'images/operators/vi_logo.svg',
                'tier_1_max'   => 199,
                'tier_1_coins' => 2,
                'tier_2_max'   => 499,
                'tier_2_coins' => 5,
                'tier_3_max'   => 999,
                'tier_3_coins' => 12,
                'tier_4_max'   => 9999,
                'tier_4_coins' => 25,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'BSNL',
                'logo_url'     => 'images/operators/bsnl_logo.png',
                'tier_1_max'   => 199,
                'tier_1_coins' => 3,
                'tier_2_max'   => 499,
                'tier_2_coins' => 7,
                'tier_3_max'   => 999,
                'tier_3_coins' => 15,
                'tier_4_max'   => 9999,
                'tier_4_coins' => 30,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        // 2. Ecommerce Platforms
        $platforms = [
            [
                'name'          => 'Amazon',
                'category'      => 'Electronics & General',
                'logo_url'      => '/assets/images/platforms/amazon_logo.svg',
                'affiliate_url' => 'https://www.amazon.in/?tag=fintech-[USER_ID]',
                'tier_1_max'    => 1000,
                'tier_1_coins'  => 10,
                'tier_2_max'    => 5000,
                'tier_2_coins'  => 50,
                'tier_3_max'    => 999999,
                'tier_3_coins'  => 150,
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Flipkart',
                'category'      => 'Fashion & Daily Needs',
                'logo_url'      => '/assets/images/Flipkart.webp',
                'affiliate_url' => 'https://www.flipkart.com/?affid=fintech-[USER_ID]',
                'tier_1_max'    => 1000,
                'tier_1_coins'  => 12,
                'tier_2_max'    => 5000,
                'tier_2_coins'  => 60,
                'tier_3_max'    => 999999,
                'tier_3_coins'  => 180,
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Myntra',
                'category'      => 'Clothing & Accessories',
                'logo_url'      => '/assets/images/platforms/myntra_logo.png',
                'affiliate_url' => 'https://www.myntra.com/?ref=fintech-[USER_ID]',
                'tier_1_max'    => 1000,
                'tier_1_coins'  => 15,
                'tier_2_max'    => 5000,
                'tier_2_coins'  => 75,
                'tier_3_max'    => 999999,
                'tier_3_coins'  => 200,
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $db = \Config\Database::connect();
        $db->table('service_recharge_operators')->insertBatch($operators);
        $db->table('service_ecommerce_platforms')->insertBatch($platforms);
    }
}
