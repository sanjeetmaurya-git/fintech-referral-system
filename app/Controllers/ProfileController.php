<?php

namespace App\Controllers;

use App\Models\UserProfileModel;
use CodeIgniter\Controller;

class ProfileController extends BaseController
{
    protected $profileModel;
    protected $userModel;

    public function __construct()
    {
        $this->profileModel = new UserProfileModel();
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        helper('encryption');
        $userId = session()->get('user_id');
        $profile = $this->profileModel->where('user_id', $userId)->first();

        // Decrypt values for display in form
        if ($profile) {
            $profile['upi_id'] = decrypt_value($profile['upi_id'] ?? '');
            $profile['bank_account_no'] = decrypt_value($profile['bank_account_no'] ?? '');
        }

        $data = [
            'title'   => 'My Profile',
            'active'  => 'profile',
            'profile' => $profile,
            'user'    => $this->userModel->find($userId)
        ];

        return view('user/profile', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        $profile = $this->profileModel->where('user_id', $userId)->first();

        $rules = [
            'full_name'       => 'required|min_length[3]|max_length[100]',
            'upi_id'          => 'permit_empty|min_length[3]|max_length[100]',
            'bank_account_no' => 'permit_empty|min_length[5]|max_length[100]',
            'ifsc_code'       => 'permit_empty|min_length[4]|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $inputs = [
            'upi_id'          => $this->request->getPost('upi_id'),
            'bank_account_no' => $this->request->getPost('bank_account_no'),
        ];

        // Detect reuse (check against both raw and encrypted if migrating, but here we assume new encrypted format)
        // Note: For strict reuse detection with encryption, we might need a separate hashed lookup table 
        // but for now, we'll check the database values. 
        // ⚠️ ENCRYPTION LIMITATION: We can't query by encrypted string easily. 
        // So for reuse detection, we should store a HASH (SHA-256) of the bank account separately.
        
        $fraudService = new \App\Services\FraudService();
        $conflicts = $fraudService->checkAccountReuse($userId, $inputs);

        if (!empty($conflicts)) {
            $fraudService->logFraud($userId, 'ACCOUNT_REUSE', implode(' ', $conflicts));
            return redirect()->back()->withInput()->with('errors', $conflicts);
        }

        helper('encryption');
        $data = [
            'user_id'         => $userId,
            'full_name'       => $this->request->getPost('full_name'),
            'upi_id'          => encrypt_value($inputs['upi_id']),
            'bank_account_no' => encrypt_value($inputs['bank_account_no']),
            'ifsc_code'       => $this->request->getPost('ifsc_code'),
            'bank_name'       => $this->request->getPost('bank_name'),
        ];

        if ($profile) {
            $this->profileModel->update($profile['id'], $data);
        } else {
            $this->profileModel->insert($data);
        }

        return redirect()->to(base_url('profile'))->with('success', 'Profile updated successfully.');
    }
}
