<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\WorkerModel;
use App\Models\WorkerDocumentModel;
use App\Models\WorkCategoryModel;
use App\Models\WorkSubcategoryModel;
use CodeIgniter\Controller;

class WorkerController extends BaseController
{
    protected $session;
    protected $workerModel;
    protected $categoryModel;
    protected $subcategoryModel;
    protected $userModel;

    public function __construct()
    {
        $this->session          = session();
        $this->workerModel      = new WorkerModel();
        $this->categoryModel    = new WorkCategoryModel();
        $this->subcategoryModel = new WorkSubcategoryModel();
        $this->userModel        = new UserModel();
    }

    public function register()
    {
        if ($this->session->get('isLoggedIn')) {
            $worker = $this->workerModel->where('user_id', $this->session->get('user_id'))->first();
            if ($worker) {
                return redirect()->to(base_url('worker/dashboard'));
            }
        }

        $data = [
            'title'      => 'Register as a Worker',
            'categories' => $this->categoryModel->where('is_active', 1)->findAll(),
            'phone'      => $this->session->get('phone') ?? '',
            'isLoggedIn' => $this->session->get('isLoggedIn') ?? false
        ];
        return view('user/worker_register', $data);
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = $this->subcategoryModel->where('category_id', $categoryId)->where('is_active', 1)->findAll();
        return $this->response->setJSON($subcategories);
    }

    public function store()
    {
        $rules = [
            'full_name'             => 'required|min_length[3]',
            'email'                 => 'required|valid_email',
            'phone'                 => 'required|min_length[10]',
            'password'              => 'required|min_length[6]',
            'confirm_password'      => 'required|matches[password]',
            'highest_qualification' => 'required',
            'address'               => 'required',
            'district'              => 'required',
            'state'                 => 'required',
            'pincode'               => 'required|numeric|exact_length[6]',
            'category_id'           => 'required',
            'subcategory_id'        => 'required',
            'skills'                => 'required|max_length[1000]',
            'experience'            => 'required|numeric',
            'aadhar_number'         => 'required|exact_length[12]',
            'pan_number'            => 'required|exact_length[10]',
            'profile_image'         => 'uploaded[profile_image]|max_size[profile_image,2048]|ext_in[profile_image,jpg,jpeg,png,svg,webp]',
            'aadhar_front'          => 'uploaded[aadhar_front]|max_size[aadhar_front,500]|ext_in[aadhar_front,jpg,jpeg,png,svg,webp]',
            'aadhar_back'           => 'uploaded[aadhar_back]|max_size[aadhar_back,500]|ext_in[aadhar_back,jpg,jpeg,png,svg,webp]',
            'pan_card'              => 'uploaded[pan_card]|max_size[pan_card,1024]|ext_in[pan_card,jpg,jpeg,png,svg,webp]',
            'declaration'           => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();

        try {
            $db->transBegin();

            // -------------------------------------------------------
            // STEP 1: Resolve / create user account
            // -------------------------------------------------------
            $phone = $this->request->getPost('phone');
            $user  = $this->userModel->where('phone', $phone)->first();

            if (!$user) {
                $userId = $this->userModel->insert([
                    'phone'         => $phone,
                    'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'referral_code' => strtoupper(uniqid('WRK')),
                    'is_active'     => 1,
                ]);
                if (!$userId) {
                    throw new \RuntimeException('Could not create user account. Phone may already exist.');
                }
                // Create Wallet
                $walletModel = new \App\Models\WalletModel();
                $walletModel->insert(['user_id' => $userId, 'balance' => 0, 'coins' => 0]);
            } else {
                $userId = $user['id'];
                // Only update password if a new one was provided (and it differs)
                $this->userModel->skipValidation(true)->update($userId, [
                    'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'is_active' => 1,
                ]);
            }

            // -------------------------------------------------------
            // STEP 2: Create / update profile (name + email)
            // -------------------------------------------------------
            $profileModel = new UserProfileModel();
            $profile      = $profileModel->where('user_id', $userId)->first();

            // Only send fields that actually exist in the table
            $profileData = [
                'full_name' => $this->request->getPost('full_name'),
                'email'     => $this->request->getPost('email'),
            ];

            if ($profile) {
                $profileModel->skipValidation(true)->update($profile['id'], $profileData);
            } else {
                $profileModel->skipValidation(true)->insert(array_merge(['user_id' => $userId], $profileData));
            }

            // -------------------------------------------------------
            // STEP 3: Create / update worker profile
            // -------------------------------------------------------
            $existingWorker = $this->workerModel->where('user_id', $userId)->first();
            $workerData = [
                'user_id'               => $userId,
                'alternate_mobile'      => $this->request->getPost('alternate_mobile'),
                'highest_qualification' => $this->request->getPost('highest_qualification'),
                'address'               => $this->request->getPost('address'),
                'district'              => $this->request->getPost('district'),
                'state'                 => $this->request->getPost('state'),
                'pincode'               => $this->request->getPost('pincode'),
                'category_id'           => $this->request->getPost('category_id'),
                'subcategory_id'        => $this->request->getPost('subcategory_id'),
                'skills'                => $this->request->getPost('skills'),
                'experience'            => $this->request->getPost('experience'),
                'aadhar_number'         => $this->request->getPost('aadhar_number'),
                'pan_number'            => $this->request->getPost('pan_number'),
                'status'                => 'pending',
            ];

            if ($existingWorker) {
                $this->workerModel->skipValidation(true)->update($existingWorker['id'], $workerData);
                $workerId = $existingWorker['id'];
            } else {
                $workerId = $this->workerModel->skipValidation(true)->insert($workerData);
            }

            if (!$workerId) {
                throw new \RuntimeException('Could not save worker profile. Please try again.');
            }

            // -------------------------------------------------------
            // STEP 4: Document uploads (outside transaction - file ops)
            // -------------------------------------------------------
            $db->transCommit(); // commit DB changes before file operations

            $docModel = new WorkerDocumentModel();
            $files    = [
                'profile_image' => 'profile_image',
                'aadhar_front'  => 'aadhar_front',
                'aadhar_back'   => 'aadhar_back',
                'pan_card'      => 'pan_card',
                'certificate'   => 'certificate',
            ];

            foreach ($files as $dbType => $inputName) {
                $file = $this->request->getFile($inputName);
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $uploadDir = WRITEPATH . 'uploads/workers/' . $workerId;
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $newName = $file->getRandomName();
                    $file->move($uploadDir, $newName);

                    // Remove old doc if re-registering
                    if ($existingWorker) {
                        $docModel->where('worker_id', $workerId)->where('document_type', $dbType)->delete();
                    }

                    $docModel->skipValidation(true)->insert([
                        'worker_id'     => $workerId,
                        'document_type' => $dbType,
                        'file_path'     => 'uploads/workers/' . $workerId . '/' . $newName,
                    ]);

                    // Store profile image path in users table
                    if ($dbType === 'profile_image') {
                        $this->userModel->skipValidation(true)->update($userId, [
                            'profile_image' => 'uploads/workers/' . $workerId . '/' . $newName,
                        ]);
                    }
                }
            }

        } catch (\Throwable $e) {
            if ($db->transStatus() !== false) {
                $db->transRollback();
            }
            log_message('error', '[WorkerController::store] ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return redirect()->back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }

        return redirect()->to(base_url('worker/success'));
    }

    public function success()
    {
        return view('user/worker_success');
    }

    public function dashboard()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $userId = $this->session->get('user_id');
        $worker = $this->workerModel->where('user_id', $userId)->first();

        if (!$worker) {
            return redirect()->to(base_url('worker/register'))->with('error', 'You are not registered as a worker. Please register first.');
        }

        if ($worker['status'] === 'pending') {
            return view('user/worker_pending');
        }

        if ($worker['status'] === 'rejected') {
            return view('user/worker_rejected');
        }

        $jobModel = new \App\Models\JobModel();
        $data = [
            'title'        => 'Worker Dashboard',
            'worker'       => $this->workerModel->getWorkerWithCategory($userId),
            'jobRequests'  => $jobModel->where('worker_id', $worker['id'])->orderBy('id', 'DESC')->findAll(),
            'active'       => 'worker_dashboard'
        ];
        return view('user/worker_dashboard', $data);
    }

    public function toggleStatus()
    {
        $userId = $this->session->get('user_id');
        $worker = $this->workerModel->where('user_id', $userId)->first();

        if ($worker) {
            $newStatus = $worker['is_online'] ? 0 : 1;
            $this->workerModel->update($worker['id'], ['is_online' => $newStatus]);
            return $this->response->setJSON(['success' => true, 'is_online' => $newStatus]);
        }
        return $this->response->setJSON(['success' => false]);
    }

    public function listCategories()
    {
        $data = [
            'title'      => 'Find Professional Workers',
            'categories' => $this->categoryModel->where('is_active', 1)->findAll(),
            'active'     => 'hire'
        ];
        return view('user/hiring/categories', $data);
    }

    public function listWorkers($categoryId)
    {
        $category = $this->categoryModel->find($categoryId);
        if (!$category) {
            return redirect()->to(base_url('hire'))->with('error', 'Category not found.');
        }

        $query = $this->workerModel->select('workers.*, up.full_name, up.email, work_categories.name as category_name')
                                   ->join('users', 'users.id = workers.user_id')
                                   ->join('user_profiles up', 'up.user_id = users.id', 'left')
                                   ->join('work_categories', 'work_categories.id = workers.category_id')
                                   ->where('workers.category_id', $categoryId)
                                   ->where('workers.status', 'approved')
                                   ->where('workers.is_online', 1);

        $data = [
            'title'    => 'Available ' . $category['name'],
            'category' => $category,
            'workers'  => $query->findAll(),
            'active'   => 'hire'
        ];
        return view('user/hiring/worker_list', $data);
    }

    public function details($id)
    {
        $worker = $this->workerModel->select('workers.*, up.full_name, up.email, work_categories.name as category_name, work_subcategories.name as subcategory_name')
                                    ->join('users', 'users.id = workers.user_id')
                                    ->join('user_profiles up', 'up.user_id = users.id', 'left')
                                    ->join('work_categories', 'work_categories.id = workers.category_id', 'left')
                                    ->join('work_subcategories', 'work_subcategories.id = workers.subcategory_id', 'left')
                                    ->where('workers.id', $id)
                                    ->where('workers.status', 'approved')
                                    ->first();

        if (!$worker) {
            return redirect()->to(base_url('hire'))->with('error', 'Worker not found or not active.');
        }

        $data = [
            'title'  => 'Hire ' . esc($worker['full_name']),
            'worker' => $worker,
            'active' => 'hire'
        ];
        return view('user/hiring/worker_details', $data);
    }

    public function hireRequest()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to hire a worker.');
        }

        $jobModel = new \App\Models\JobModel();
        
        $data = [
            'user_id'     => $this->session->get('user_id'),
            'worker_id'   => $this->request->getPost('worker_id'),
            'category_id' => $this->request->getPost('category_id'),
            'description' => $this->request->getPost('description'),
            'budget'      => $this->request->getPost('budget'),
            'location'    => $this->request->getPost('location'),
            'status'      => 'requested'
        ];

        if ($jobModel->insert($data)) {
            return redirect()->to(base_url('hire/details/' . $data['worker_id']))->with('success', 'Hiring request sent successfully! The worker will contact you soon.');
        }

        return redirect()->back()->with('error', 'Failed to send request.');
    }
}
