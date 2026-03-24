<?php

namespace App\Services;

class NotificationService
{
    /**
     * Send OTP via Log (Free Testing) or SMS (Paid Future)
     */
    public function sendOtp(string $phone, int $otp): bool
    {
        // 1. Keep the local text file logging (as requested)
        $logPath = WRITEPATH . 'logs/otp.log';
        $message = "[" . date('Y-m-d H:i:s') . "] OTP for {$phone}: {$otp}" . PHP_EOL;
        file_put_contents($logPath, $message, FILE_APPEND);

        // 2. Log to the default CI4 log for visibility
        log_message('error', "OTP Attempt for {$phone} -> {$otp}"); // Using error level to ensure it logs regardless of threshold

        // 3. Send via Spring Edge API
        // Always use CI4 env() helper instead of getenv()
        $apiUrl   = env('SMS_API_URL');
        $apiKey   = env('SMS_API_KEY');
        $senderId = env('SMS_SENDER_ID');

        // Only send if API details are configured
        if (!empty($apiUrl) && !empty($apiKey) && !empty($senderId) && $apiKey !== 'your_api_key_here') {
            try {
                // Ensure phone number starts with country code (e.g., 91 for India)
                // Spring Edge requires country code without '+'
                $formattedPhone = ltrim($phone, '+');
                if (strlen($formattedPhone) == 10) {
                    $formattedPhone = '91' . $formattedPhone;
                }

                // Parse the SMS Template from .env to avoid "Invalid Template Match" DLT errors
                $template = env('SMS_TEMPLATE');
                if (empty($template)) {
                    $template = "Your OTP for login is {#var#}. Do not share this with anyone.";
                }
                
                // Replace common placeholders with the actual OTP
                $smsMessage = str_replace(['{#var#}', '{{otp}}', '{otp}', '[OTP]', '$otp'], $otp, $template);
                $encoded_message = urlencode($smsMessage);

                // Prepare API URL exactly like the test script
                $api_url = $apiUrl . "?apikey=" . $apiKey . "&sender=" . $senderId . "&to=" . $formattedPhone . "&message=" . $encoded_message;

                // Call Spring Edge API using native cURL (mirroring working test script)
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local development
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $response = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);

                if ($error) {
                    log_message('error', "Failed to connect to SMS server: " . $error);
                } else {
                    log_message('error', "Spring Edge SMS Response: {$response}");
                    
                    if (strpos($response, 'Invalid API Key') !== false || strpos($response, 'error') !== false || strpos($response, 'Invalid') !== false) {
                        log_message('error', "Spring Edge SMS Error: " . strip_tags($response));
                    }
                }
            } catch (\Exception $e) {
                log_message('error', "Failed to send OTP via SMS Exception: " . $e->getMessage());
            }
        } else {
             log_message('error', "SMS_API variables not set in .env. Falling back to local log only.");
        }

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
