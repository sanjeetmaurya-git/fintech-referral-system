<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceTables extends Migration
{
    public function up()
    {
        // 1. Recharge Operators
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'name'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'logo_url'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tier_1_max'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_1_coins' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_2_max'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_2_coins' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_3_max'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_3_coins' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_4_max'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_4_coins' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'is_active'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('service_recharge_operators');

        // 2. Ecommerce Platforms
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'name'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'category'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'logo_url'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'affiliate_url'=> ['type' => 'TEXT', 'null' => true],
            'tier_1_max'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_1_coins' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_2_max'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_2_coins' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_3_max'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tier_3_coins' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'is_active'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('service_ecommerce_platforms');

        // 3. Service Transactions
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'user_id'      => ['type' => 'INT', 'null' => false],
            'service_type' => ['type' => 'ENUM', 'constraint' => ['recharge', 'ecommerce'], 'default' => 'recharge'],
            'platform_id'  => ['type' => 'INT', 'null' => true], // refers to ecommerce platform
            'operator_id'  => ['type' => 'INT', 'null' => true], // refers to recharge operator
            'amount'       => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'coins_earned' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'status'       => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected'], 'default' => 'pending'],
            'reference_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'admin_notes'  => ['type' => 'TEXT', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->createTable('service_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('service_transactions');
        $this->forge->dropTable('service_ecommerce_platforms');
        $this->forge->dropTable('service_recharge_operators');
    }
}
