<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWalletTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['credit', 'debit'],
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'reference_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                    'constraint' => ['pending', 'approved', 'rejected'],
                    'default' => 'pending',
                ],
                'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('user_id');

            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

            $this->forge->createTable('wallet_transactions');
    }


    public function down()
    {
        //
    }
}
