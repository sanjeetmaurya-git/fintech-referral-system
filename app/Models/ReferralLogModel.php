<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferralLogModel extends Model
{
    protected $table            = 'referral_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'referrer_id',
        'referred_user_id',
        'referral_code',
        'ip_address',
        'description',
    ];

    // Dates
    protected $useTimestamps = false;
}
