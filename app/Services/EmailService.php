<?php

namespace App\Services;

use Config\Services;

class EmailService
{
    /**
     * Central method to send HTML emails
     */
    public function send(string $to, string $subject, string $view, array $data = []): bool
    {
        $email = Services::email();
        $config = config('Email');

        // Set from address if configured, else fallback
        $fromEmail = !empty($config->fromEmail) ? $config->fromEmail : 'no-reply@fintech.test';
        $fromName  = !empty($config->fromName) ? $config->fromName : 'FinTech Referrals';

        $email->setFrom($fromEmail, $fromName);
        $email->setTo($to);
        $email->setSubject($subject);
        
        $email->setMessage(view($view, $data));

        // If SMTP is not set, we just log it for dev
        if (empty($config->SMTPHost) && $config->protocol === 'smtp') {
            log_message('notice', "Email to {$to} (Subject: {$subject}) was NOT sent because SMTP is unconfigured. View: {$view}");
            return true; 
        }

        try {
            return $email->send();
        } catch (\Exception $e) {
            log_message('error', "Email failed: " . $e->getMessage());
            return false;
        }
    }
}
