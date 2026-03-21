<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVerificationToWorkerDocuments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('worker_documents', [
            'is_verified' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'after'   => 'file_path',
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'is_verified',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('worker_documents', 'is_verified');
        $this->forge->dropColumn('worker_documents', 'verified_at');
    }
}
