<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOtpsTable extends Migration
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
            'otp_code' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'is_used' => [
                'type' => 'BOOLEAN',
                'default' => 0,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('phone');

        $this->forge->createTable('otps');
    }


    public function down()
    {
        //
    }
}
