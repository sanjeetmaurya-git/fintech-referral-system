<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class JoinController extends BaseController
{
    /**
     * Handle shareable referral links: /join/{referral_code}
     */
    public function index($referralCode = null)
    {
        if (!$referralCode) {
            return redirect()->to('/login');
        }

        // Validate if referral code exists
        $userModel = new UserModel();
        $user = $userModel->where('referral_code', $referralCode)->first();

        if ($user) {
            // Store referral code in session or cookie for later use during registration
            session()->set('pending_referral', $referralCode);
            
            // Set a cookie that lasts for 30 days (optional, for persistent tracking)
            setcookie('refer_code', $referralCode, time() + (86400 * 30), "/");
        }

        // Redirect to registration/login page
        // In a real mobile scenario, this might be a dynamic link redirecting to App Store
        return redirect()->to('/login')->with('message', 'Welcome! You were referred by a friend.');
    }
}
