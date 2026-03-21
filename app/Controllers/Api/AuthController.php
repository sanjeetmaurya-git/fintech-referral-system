<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OtpModel;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Services\ReferralService;

class AuthController extends BaseController
{
    public function index()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'FinTech Referral API Running'
        ]);
    }

    // ===============================
    // 1️⃣ REGISTER (Generate OTP)
    // ===============================
    public function register()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['phone'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Phone is required'
            ]);
        }

        $phone = $data['phone'];
        $otp = rand(100000, 999999);

        $otpModel = new OtpModel();

        $otpModel->insert([
            'phone'      => $phone,
            'otp_code'   => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'is_used'    => 0
        ]);

        // Send OTP (Free Testing via Log)
        $notificationService = new \App\Services\NotificationService();
        $notificationService->sendOtp($phone, $otp);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'OTP sent successfully (Free Mode: Check writable/logs/otp.log)',
            'otp_dev' => $otp // Keeping for ease of dev, can be removed
        ]);
    }

    // ===============================
    // 2️⃣ VERIFY OTP
    // ===============================
    public function verifyOtp()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['phone']) || empty($data['otp'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Phone and OTP required'
            ]);
        }

        $phone = $data['phone'];
        $otpInput = $data['otp'];

        $otpModel = new OtpModel();
        $userModel = new UserModel();
        $walletModel = new WalletModel();

        $otpRecord = $otpModel
            ->where('phone', $phone)
            ->where('otp_code', $otpInput)
            ->where('is_used', 0)
            ->first();

        if (!$otpRecord) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid OTP'
            ]);
        }

        if (strtotime($otpRecord['expires_at']) < time()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'OTP expired'
            ]);
        }

        // Mark OTP used
        $otpModel->update($otpRecord['id'], ['is_used' => 1]);

        // Check if user exists
        $user = $userModel->where('phone', $phone)->first();
        $isNewUser = false;

        if (!$user) {
            $isNewUser = true;
            
            // Capture referral code: 1. Request Body, 2. Session, 3. Cookie
            $referralCode = $data['referral_code'] ?? null;
            if (empty($referralCode)) {
                $referralCode = session()->get('pending_referral');
            }
            if (empty($referralCode)) {
                $referralCode = $_COOKIE['refer_code'] ?? null;
            }

            $deviceId = $data['device_id'] ?? null;
            $ipAddress = $this->request->getIPAddress();

            // Phase 11: IP Velocity Check
            $fraudService = new \App\Services\FraudService();
            if (!$fraudService->checkIpVelocity($ipAddress)) {
                $fraudService->logFraud(0, 'IP_VELOCITY', "Blocked registration attempt from {$ipAddress} - Too many requests.");
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Security Block: Too many registration attempts from this IP. Please try later.'
                ]);
            }

            $userId = $userModel->insert([
                'phone'         => $phone,
                'password'      => !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null,
                'referral_code' => strtoupper(uniqid('REF')),
                'device_id'     => $deviceId,
                'ip_address'    => $ipAddress,
                'is_active'     => 1,
                'last_login_at' => date('Y-m-d H:i:s'),
            ]);

            // Create wallet
            $walletModel->insert([
                'user_id' => $userId,
                'balance' => 0.00
            ]);

            // Process Referral (Phase 4 & 5)
            $referralService = new ReferralService();
            $referralService->processReferral($userId, $referralCode, $ipAddress);

            $user = $userModel->find($userId);
        } else {
            $userModel->update($user['id'], [
                'is_active'     => 1,
                'last_login_at' => date('Y-m-d H:i:s')
            ]);
            $userId = $user['id'];
        }

        // Send Welcome Email if new
        if ($isNewUser) {
            $notificationService = new \App\Services\NotificationService();
            $notificationService->sendWelcome($user);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'User verified successfully',
            'user_id' => $userId,
            'is_new'  => $isNewUser
        ]);
    }
}
