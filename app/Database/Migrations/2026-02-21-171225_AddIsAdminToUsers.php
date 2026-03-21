<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsAdminToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'is_admin' => [
                'type' => 'BOOLEAN',
                'default' => 0,
                'after' => 'is_active'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_admin');
    }
}
