<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWithdrawalsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'payment_details' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'completed', 'rejected'],
                'default'    => 'pending',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('withdrawals');
    }

    public function down()
    {
        $this->forge->dropTable('withdrawals');
    }
}
