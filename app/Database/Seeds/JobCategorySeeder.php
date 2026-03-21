<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Home Maintenance', 'icon' => 'bi-house-gear'],
            ['name' => 'Electrician', 'icon' => 'bi-lightning'],
            ['name' => 'Plumbing', 'icon' => 'bi-drop'],
            ['name' => 'Cleaning', 'icon' => 'bi-stars'],
            ['name' => 'AC & Appliances', 'icon' => 'bi-fan'],
            ['name' => 'Digital Services', 'icon' => 'bi-laptop'],
        ];

        $db = \Config\Database::connect();

        // Prevent duplicate seeding
        $existing = $db->table('work_categories')->countAll();
        if ($existing > 0) {
            echo "Categories already seeded. Skipping.\n";
            return;
        }

        foreach ($categories as $cat) {
            $db->table('work_categories')->insert([
                'name'       => $cat['name'],
                'icon'       => $cat['icon'],
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $catId = $db->insertID();

            // Add some subcategories
            $subcategories = [];
            if ($cat['name'] == 'Electrician') {
                $subcategories = ['Fan Repair', 'Wiring', 'Switch Board', 'Inverter'];
            } elseif ($cat['name'] == 'Plumbing') {
                $subcategories = ['Tap Repair', 'Tank Leakage', 'Pipe Fitting', 'Bathroom Fitting'];
            } elseif ($cat['name'] == 'Cleaning') {
                $subcategories = ['Deep Home Cleaning', 'Kitchen Cleaning', 'Bathroom Cleaning', 'Sofa Cleaning'];
            }

            foreach ($subcategories as $sub) {
                $db->table('work_subcategories')->insert([
                    'category_id' => $catId,
                    'name'        => $sub,
                    'is_active'   => 1,
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
