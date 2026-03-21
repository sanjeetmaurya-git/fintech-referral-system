<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeToServiceOperators extends Migration
{
    public function up()
    {
        $this->forge->addColumn('service_recharge_operators', [
            'service_type' => [
                'type'       => 'ENUM',
                'constraint' => ['mobile', 'd2h'],
                'default'    => 'mobile',
                'after'      => 'name'
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'service_type'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('service_recharge_operators', ['service_type', 'category']);
    }
}
