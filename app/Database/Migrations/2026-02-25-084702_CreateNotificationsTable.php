<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'null' => false],
            'type'       => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'info'],
            'title'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'message'    => ['type' => 'TEXT'],
            'icon'       => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'bi-bell'],
            'is_read'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->createTable('notifications');
    }

    public function down()
    {
        $this->forge->dropTable('notifications');
    }
}
