<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\WalletModel;
use App\Models\UserProfileModel;

class Home extends BaseController
{
    public function index(): string
    {
        $session = session();
        $data = [
            'isLoggedIn' => $session->get('isLoggedIn'),
            'title'      => 'SmartLead Fintech Services',
            'active'     => 'home'
        ];

        if ($data['isLoggedIn']) {
            $userModel = new UserModel();
            $walletModel = new WalletModel();
            $profileModel = new UserProfileModel();
            $userId = $session->get('user_id');
            
            $data['user']    = $userModel->find($userId);
            $data['wallet']  = $walletModel->where('user_id', $userId)->first();
            $data['profile'] = $profileModel->where('user_id', $userId)->first();
        }

        return view('home', $data);
    }
}
