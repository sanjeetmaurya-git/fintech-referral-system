<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembershipOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'payment_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // 'wallet' or 'coins'
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('membership_orders');
    }

    public function down()
    {
        $this->forge->dropTable('membership_orders');
    }
}
