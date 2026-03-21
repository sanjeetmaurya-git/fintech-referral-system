<?php

namespace App\Models;

use CodeIgniter\Model;

class WithdrawalModel extends Model
{
    protected $table            = 'withdrawals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'amount',
        'payment_details',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id'         => 'required|is_not_unique[users.id]',
        'amount'          => 'required|decimal|greater_than[0]',
        'payment_details' => 'required',
        'status'          => 'required|in_list[pending,completed,rejected]',
    ];
}
