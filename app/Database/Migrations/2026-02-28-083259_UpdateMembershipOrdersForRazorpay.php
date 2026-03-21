<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateMembershipOrdersForRazorpay extends Migration
{
    public function up()
    {
        $fields = [
            'razorpay_order_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'amount',
            ],
            'razorpay_payment_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'razorpay_order_id',
            ],
            'razorpay_signature' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'razorpay_payment_id',
            ],
        ];
        $this->forge->addColumn('membership_orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('membership_orders', 'razorpay_order_id');
        $this->forge->dropColumn('membership_orders', 'razorpay_payment_id');
        $this->forge->dropColumn('membership_orders', 'razorpay_signature');
    }
}
