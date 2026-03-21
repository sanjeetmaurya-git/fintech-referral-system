<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReferralLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
            'type' => 'BIGINT',
            'unsigned' => true,
            'auto_increment' => true,
            ],
            'referrer_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'referred_user_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'referral_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addKey('referrer_id');
    $this->forge->addKey('referred_user_id');

    $this->forge->addForeignKey('referrer_id', 'users', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('referred_user_id', 'users', 'id', 'SET NULL', 'CASCADE');

    $this->forge->createTable('referral_logs');
}


    public function down()
    {
        //
    }
}
