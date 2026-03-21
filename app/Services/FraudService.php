<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\ReferralLogModel;

class FraudService
{
    protected $userModel;
    protected $profileModel;
    protected $logModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->profileModel = new UserProfileModel();
        $this->logModel     = new ReferralLogModel();
    }

    /**
     * Check if an IP address is registering too many accounts.
     * Rule: Max 5 registrations per IP in 10 minutes.
     */
    public function checkIpVelocity(string $ip): bool
    {
        $limit = 5;
        $minutes = 10;
        $timestamp = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));

        $count = $this->userModel->where('ip_address', $ip)
                                 ->where('created_at >=', $timestamp)
                                 ->countAllResults();

        return $count < $limit; // Returns true if safe, false if fraud suspected
    }

    /**
     * Check if a bank account or UPI ID is already used by another user.
     */
    public function checkAccountReuse(int $userId, array $details): array
    {
        $conflicts = [];

        if (!empty($details['upi_id'])) {
            $existing = $this->profileModel->where('upi_id', $details['upi_id'])
                                           ->where('user_id !=', $userId)
                                           ->first();
            if ($existing) $conflicts[] = "UPI ID already linked to another account.";
        }

        if (!empty($details['bank_account_no'])) {
            $existing = $this->profileModel->where('bank_account_no', $details['bank_account_no'])
                                           ->where('user_id !=', $userId)
                                           ->first();
            if ($existing) $conflicts[] = "Bank Account already linked to another account.";
        }

        return $conflicts;
    }

    /**
     * Log a fraud attempt.
     */
    public function logFraud(int $userId, string $type, string $description)
    {
        $this->logModel->insert([
            'referrer_id'      => 0, // System
            'referred_user_id' => $userId,
            'ip_address'       => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'description'      => "FRAUD_{$type}: {$description}"
        ]);
    }
}
