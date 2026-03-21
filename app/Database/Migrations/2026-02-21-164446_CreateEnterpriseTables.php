<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnterpriseTables extends Migration
{
    public function up()
    {
        // 1. User Profiles Table
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
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'upi_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'bank_account_no' => [
                'type'       => 'VARCHAR',
                'constraint' => '255', // Encrypted maybe, so longer
                'null'       => true,
            ],
            'ifsc_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'bank_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_profiles');

        // 2. Wallet Ledger Table (Double Entry Style)
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['credit', 'debit'],
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'balance_after' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'reference_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('wallet_ledger');

        // 3. Support Tickets
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
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'urgent'],
                'default'    => 'medium',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['open', 'in-progress', 'pending_user', 'resolved', 'closed'],
                'default'    => 'open',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('support_tickets');

        // 4. Ticket Messages (Threaded)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'sender_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'is_admin_reply' => [
                'type'    => 'TINYINT',
                'default' => 0,
            ],
            'attachment' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('ticket_id', 'support_tickets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ticket_messages');
    }

    public function down()
    {
        $this->forge->dropTable('ticket_messages');
        $this->forge->dropTable('support_tickets');
        $this->forge->dropTable('wallet_ledger');
        $this->forge->dropTable('user_profiles');
    }
}
