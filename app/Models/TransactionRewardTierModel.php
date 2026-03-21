<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionRewardTierModel extends Model
{
    protected $table            = 'transaction_reward_tiers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['min_amount', 'max_amount', 'reward_coins'];
}
