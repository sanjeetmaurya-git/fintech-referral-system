<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletLedgerModel extends Model
{
    protected $table            = 'wallet_ledger';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'type', 'amount', 'balance_after', 'reference_id', 'description'];

    // Dates
    protected $useTimestamps = false; // Custom created_at only
}
