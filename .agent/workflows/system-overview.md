---
description: Technical overview and verification workflow for the Referral & Coin System
---

This workflow helps an agent quickly understand and verify the core components of the referral system.

### 1. System Components
- **Routes**: Defined in `app/Config/Routes.php` (Look for `prefix('api')` and user routes).
- **Core Logic**: `app/Services/ReferralService.php` contains the reward distribution and MLM logic.
- **Controllers**: 
    - `UserDashboardController.php`: Handles dashboard, auto-approval, and coin redemption.
    - `Api/AuthController.php`: Handles mobile registration and password logic.
- **Models**: `UserModel`, `WalletModel`, `WalletTransactionModel`, `SettingModel`.

### 2. Verification Workflow
To verify the system logic after changes:

// turbo
1. Run the automated verification command:
```powershell
php spark referral:verify
```

2. Review the output for:
    - ₹100 split correctness (Test 1).
    - Referral reward logging (Test 2).
    - Auto-approval execution (Test 3).
    - Coin MLM bonus distribution (Test 3).

### 3. Key Data Points
- **Settings**: Check `settings` table for `coin_rate`, `min_redemption`, and `base_reward_amount`.
- **Transactions**: Check `wallet_transactions` for `reference_id` patterns like `REF-ID-LX`.
- **Users**: Check `users` table for `has_done_first_tx` and `last_login_at`.

### 4. Common Tasks
- **Update Reward Amount**: Modify `base_reward_amount` in settings.
- **Adjust Coin Rate**: Modify `coin_rate` (default 5).
- **Manual Approval**: Update `status` to `approved` in `wallet_transactions` table.
