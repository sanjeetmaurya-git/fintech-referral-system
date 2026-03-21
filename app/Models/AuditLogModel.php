<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table         = 'audit_logs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['admin_id', 'action', 'description', 'ip_address'];
    protected $useTimestamps = true;
    protected $updatedField  = ''; // No updated_at for audit logs

    public function log(int $adminId, string $action, string $description)
    {
        $this->insert([
            'admin_id'    => $adminId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => service('request')->getIPAddress(),
        ]);
    }
}
