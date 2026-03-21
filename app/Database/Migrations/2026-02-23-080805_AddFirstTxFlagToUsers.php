<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFirstTxFlagToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'has_done_first_tx' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'last_login_at'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'has_done_first_tx');
    }
}
