<?php

namespace App\Services;

class NotificationService
{
    /**
     * Send OTP via Log (Free Testing) or SMS (Paid Future)
     */
    public function sendOtp(string $phone, int $otp): bool
    {
        // For free testing, we write to a dedicated log file
        $logPath = WRITEPATH . 'logs/otp.log';
        $message = "[" . date('Y-m-d H:i:s') . "] OTP for {$phone}: {$otp}" . PHP_EOL;

        file_put_contents($logPath, $message, FILE_APPEND);

        // Also log to the default CI4 log for visibility in debug bar
        log_message('info', "Free OTP Test: {$phone} -> {$otp}");

        return true;
    }

    /**
     * Send Welcome Email
     */
    public function sendWelcome(array $user): bool
    {
        $emailService = new EmailService();
        return $emailService->send(
            $user['phone'],
            'Welcome to FinTech Referral!',
            'emails/welcome',
            ['user' => $user]
        );
    }

    /**
     * Send Reward update email
     */
    public function sendRewardUpdate(array $user, float $amount, string $status): bool
    {
        $emailService = new EmailService();
        $subject = ($status === 'approved') ? 'Reward Approved! 🎉' : 'Reward Update';
        $view    = 'emails/reward_' . $status;

        return $emailService->send(
            $user['phone'],
            $subject,
            $view,
            ['user' => $user, 'amount' => $amount]
        );
    }
}
