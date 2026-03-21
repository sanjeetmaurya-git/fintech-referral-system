<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceTransactionModel extends Model
{
    protected $table            = 'service_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'service_type', 'platform_id', 'operator_id',
        'amount', 'coins_earned', 'status', 'reference_id', 'admin_notes'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPending()
    {
        return $this->select('service_transactions.*, users.phone as user_phone, users.username, service_recharge_operators.name as operator_name, service_ecommerce_platforms.name as platform_name')
                    ->join('users', 'users.id = service_transactions.user_id')
                    ->join('service_recharge_operators', 'service_recharge_operators.id = service_transactions.operator_id', 'left')
                    ->join('service_ecommerce_platforms', 'service_ecommerce_platforms.id = service_transactions.platform_id', 'left')
                    ->where('service_transactions.status', 'pending')
                    ->findAll();
    }
}
