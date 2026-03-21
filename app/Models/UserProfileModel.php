<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $table            = 'user_profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'full_name', 'upi_id', 'bank_account_no', 'ifsc_code', 'bank_name'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
