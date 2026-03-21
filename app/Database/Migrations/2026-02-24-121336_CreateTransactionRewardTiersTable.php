<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionRewardTiersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'min_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'max_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'reward_coins' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('transaction_reward_tiers');

        // Optional: Pre-seed some default tiers
        $db = \Config\Database::connect();
        $db->table('transaction_reward_tiers')->insertBatch([
            ['min_amount' => 0,   'max_amount' => 100,  'reward_coins' => 2],
            ['min_amount' => 101, 'max_amount' => 200,  'reward_coins' => 4],
            ['min_amount' => 201, 'max_amount' => 500,  'reward_coins' => 10],
            ['min_amount' => 501, 'max_amount' => 1000, 'reward_coins' => 20],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('transaction_reward_tiers');
    }
}
