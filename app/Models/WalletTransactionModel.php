<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletTransactionModel extends Model
{
    protected $table            = 'wallet_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'type',
        'amount',
        'reference_id',
        'description',
        'status',
    ];

    // Dates
    protected $useTimestamps = false; // migration handles created_at only

    // Validation
    protected $validationRules      = [
        'user_id' => 'required',
        'type'    => 'required|in_list[credit,debit]',
        'amount'  => 'required|decimal',
        'status'  => 'required|in_list[pending,approved,rejected]',
    ];
}
