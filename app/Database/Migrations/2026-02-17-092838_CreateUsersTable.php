<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
            'type' => 'BIGINT',
            'unsigned' => true,
            'auto_increment' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'referral_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'referred_by' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'device_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => 0,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('phone');
        $this->forge->addUniqueKey('referral_code');
        $this->forge->addKey('referred_by');

        $this->forge->addForeignKey('referred_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('users');
    }


    public function down()
    {
        //
    }
}
