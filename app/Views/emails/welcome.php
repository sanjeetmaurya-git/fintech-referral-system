<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-bottom: 4px solid #6366f1; border-radius: 8px; }
        .header { text-align: center; margin-bottom: 30px; }
        .referral-box { background: #eef2ff; border: 2px dashed #6366f1; padding: 15px; text-align: center; border-radius: 8px; font-weight: bold; font-size: 20px; color: #4338ca; }
        .footer { font-size: 12px; color: #666; text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #6366f1;">Welcome to FinTech! 🚀</h1>
        </div>
        <p>Hello,</p>
        <p>Thank you for joining our platform. Your account has been successfully verified.</p>
        
        <p>Start earning rewards by sharing your unique referral code with friends and family:</p>
        
        <div class="referral-box">
            <?= $user['referral_code'] ?>
        </div>

        <p><strong>How it works:</strong><br>
        1. Share your code.<br>
        2. When someone registers using your code, you earn a reward instantly!<br>
        3. Earn rewards up to 8 levels deep in your network.</p>

        <p>Happy Referral Earning!</p>
        
        <div class="footer">
            &copy; <?= date('Y') ?> FinTech Referral System. All rights reserved.
        </div>
    </div>
</body>
</html>
