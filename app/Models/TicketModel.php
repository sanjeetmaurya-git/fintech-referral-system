<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table            = 'support_tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'subject', 'priority', 'status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getFullTicket(int $id)
    {
        return $this->select('support_tickets.*, users.phone as user_phone')
                    ->join('users', 'users.id = support_tickets.user_id')
                    ->find($id);
    }
}
