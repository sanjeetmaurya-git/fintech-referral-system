<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkerDocumentModel extends Model
{
    protected $table            = 'worker_documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['worker_id', 'document_type', 'file_path', 'is_verified', 'verified_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null;
}
