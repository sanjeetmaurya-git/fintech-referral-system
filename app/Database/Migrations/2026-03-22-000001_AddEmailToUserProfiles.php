<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailToUserProfiles extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user_profiles', [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'full_name',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('user_profiles', 'email');
    }
}
