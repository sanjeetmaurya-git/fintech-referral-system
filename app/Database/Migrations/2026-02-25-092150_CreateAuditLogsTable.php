<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'admin_id'    => ['type' => 'INT', 'null' => true],
            'action'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'TEXT'],
            'ip_address'  => ['type' => 'VARCHAR', 'constraint' => 45],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('admin_id');
        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}
