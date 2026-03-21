<?php

namespace App\Models;

use CodeIgniter\Model;

class MembershipOrderModel extends Model
{
    protected $table            = 'membership_orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'payment_type',
        'amount',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'status',
        'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get orders with user details
     */
    public function getOrdersWithUser(?string $status = null)
    {
        $builder = $this->select('membership_orders.*, users.phone, users.referral_code')
                        ->join('users', 'users.id = membership_orders.user_id');
        
        if ($status) {
            $builder->where('membership_orders.status', $status);
        }

        return $builder->orderBy('membership_orders.id', 'DESC')->findAll();
    }
}
