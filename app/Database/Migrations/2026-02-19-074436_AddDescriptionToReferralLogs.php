<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescriptionToReferralLogs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('referral_logs', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'ip_address'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('referral_logs', 'description');
    }
}
