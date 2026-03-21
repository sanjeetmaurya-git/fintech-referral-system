<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background: #6366f1; color: #fff; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .otp-box { font-size: 32px; font-weight: bold; background: #f3f4f6; padding: 20px; text-align: center; border-radius: 8px; margin: 20px 0; letter-spacing: 5px; color: #111827; }
        .footer { font-size: 12px; color: #666; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Your OTP Code</h2>
        </div>
        <p>Hello,</p>
        <p>You requested a login OTP for the FinTech Referral System. Please use the code below to verify your account:</p>
        
        <div class="otp-box"><?= $otp ?></div>

        <p>This code will expire in 5 minutes. If you did not request this, please ignore this email.</p>
        
        <div class="footer">
            &copy; <?= date('Y') ?> FinTech Referral System
        </div>
    </div>
</body>
</html>
