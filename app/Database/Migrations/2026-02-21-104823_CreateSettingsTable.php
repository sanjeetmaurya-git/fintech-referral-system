<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'group' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'general',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('settings');

        // Insert Default Values
        $db = \Config\Database::connect();
        $builder = $db->table('settings');

        $defaultSettings = [
            ['key' => 'base_reward_amount', 'value' => '10.00', 'group' => 'rewards', 'description' => 'Standard base amount for referral calculations'],
            ['key' => 'min_withdrawal',     'value' => '100.00', 'group' => 'withdrawals', 'description' => 'Minimum amount required for a withdrawal request'],
            // Level-wise percentages (Phase 5: 50, 15, 11, 8, 6, 4, 3, 3)
            ['key' => 'reward_level_1', 'value' => '50', 'group' => 'rewards', 'description' => 'Percentage for Level 1 (Direct)'],
            ['key' => 'reward_level_2', 'value' => '15', 'group' => 'rewards', 'description' => 'Percentage for Level 2'],
            ['key' => 'reward_level_3', 'value' => '11', 'group' => 'rewards', 'description' => 'Percentage for Level 3'],
            ['key' => 'reward_level_4', 'value' => '8',  'group' => 'rewards', 'description' => 'Percentage for Level 4'],
            ['key' => 'reward_level_5', 'value' => '6',  'group' => 'rewards', 'description' => 'Percentage for Level 5'],
            ['key' => 'reward_level_6', 'value' => '4',  'group' => 'rewards', 'description' => 'Percentage for Level 6'],
            ['key' => 'reward_level_7', 'value' => '3',  'group' => 'rewards', 'description' => 'Percentage for Level 7'],
            ['key' => 'reward_level_8', 'value' => '3',  'group' => 'rewards', 'description' => 'Percentage for Level 8'],
        ];

        $builder->insertBatch($defaultSettings);
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
