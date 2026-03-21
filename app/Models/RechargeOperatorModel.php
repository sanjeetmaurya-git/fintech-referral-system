<?php

namespace App\Models;

use CodeIgniter\Model;

class RechargeOperatorModel extends Model
{
    protected $table            = 'service_recharge_operators';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'name', 'logo_url',
        'tier_1_max', 'tier_1_coins',
        'tier_2_max', 'tier_2_coins',
        'tier_3_max', 'tier_3_coins',
        'tier_4_max', 'tier_4_coins',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}