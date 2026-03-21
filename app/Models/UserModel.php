<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'phone',
        'password',
        'referral_code',
        'referred_by',
        'device_id',
        'ip_address',
        'is_active',
        'last_login_at',
        'has_done_first_tx',
        'is_premium',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'phone'         => 'required|is_unique[users.phone,id,{id}]|min_length[10]|max_length[15]',
        'referral_code' => 'required|is_unique[users.referral_code,id,{id}]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
