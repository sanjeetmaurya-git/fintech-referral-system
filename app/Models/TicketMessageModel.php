<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketMessageModel extends Model
{
    protected $table            = 'ticket_messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['ticket_id', 'sender_id', 'message', 'is_admin_reply', 'attachment'];

    // Dates
    protected $useTimestamps = false; 
}
