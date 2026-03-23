<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkerModel extends Model
{
    protected $table            = 'workers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'alternate_mobile', 'highest_qualification', 
        'address', 'district', 'state', 'pincode', 
        'category_id', 'subcategory_id', 'skills', 
        'experience', 'aadhar_number', 'pan_number', 
        'status', 'is_online'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWorkerWithCategory($userId)
    {
        return $this->select('workers.*, work_categories.name as category_name, work_subcategories.name as subcategory_name, users.profile_image, user_profiles.full_name')
                    ->join('work_categories', 'work_categories.id = workers.category_id', 'left')
                    ->join('work_subcategories', 'work_subcategories.id = workers.subcategory_id', 'left')
                    ->join('users', 'users.id = workers.user_id', 'left')
                    ->join('user_profiles', 'user_profiles.user_id = workers.user_id', 'left')
                    ->where('workers.user_id', $userId)
                    ->first();
    }
}
