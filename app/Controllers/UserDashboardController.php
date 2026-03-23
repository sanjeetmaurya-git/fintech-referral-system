<?php

namespace App\Controllers;

use App\Models\OtpModel;
use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\WithdrawalModel;
use App\Services\NotificationService;
use App\Services\RazorpayService;
use CodeIgniter\Controller;

class UserDashboardController extends BaseController
{
    protected $session;
    protected $userModel;
    protected $walletModel;
    protected $transactionModel;
    protected $withdrawalModel;

    public function __construct()
    {
        $this->session          = session();
        $this->userModel        = new UserModel();
        $this->walletModel      = new WalletModel();
        $this->transactionModel = new WalletTransactionModel();
        $this->withdrawalModel  = new WithdrawalModel();
    }

    // ==========================
    // LOGIN (Step 1: Phone)
    // ==========================
    public function login()
    {
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('user/login');
    }

    // ==========================
    // SEND OTP (POST)
    // ==========================
    public function sendOtp()
    {
        $phone = $this->request->getPost('phone');

        if (empty($phone)) {
            return redirect()->back()->with('error', 'Phone number is required');
        }

        $otp = rand(100000, 999999);
        $otpModel = new OtpModel();

        $otpModel->insert([
            'phone'      => $phone,
            'otp_code'   => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'is_used'    => 0
        ]);

        // Send OTP via Service (Log for free testing)
        $notification = new NotificationService();
        $notification->sendOtp($phone, $otp);

        $this->session->setFlashdata('phone', $phone);
        return redirect()->to(base_url('verify'))->with('success', 'OTP sent! Check your sms/logs.');
    }

    // ==========================
    // VERIFY OTP (Step 2)
    // ==========================
    public function verify()
    {
        $phone = $this->session->getFlashdata('phone');
        if (empty($phone)) {
            // Check if it's already in permanent session for retry
            $phone = $this->session->get('temp_phone');
        }

        if (empty($phone)) {
            return redirect()->to(base_url('login'));
        }

        $this->session->set('temp_phone', $phone);
        return view('user/verify', ['phone' => $phone]);
    }

    // ==========================
    // DO VERIFY (POST)
    // ==========================
    public function doVerify()
    {
        $phone = $this->session->get('temp_phone');
        $otp   = $this->request->getPost('otp');

        $otpModel = new OtpModel();
        $otpRecord = $otpModel
            ->where('phone', $phone)
            ->where('otp_code', $otp)
            ->where('is_used', 0)
            ->first();

        if (!$otpRecord || strtotime($otpRecord['expires_at']) < time()) {
            return redirect()->back()->with('error', 'Invalid or expired OTP');
        }

        // Mark used
        $otpModel->update($otpRecord['id'], ['is_used' => 1]);

        // Get or Create user
        $user = $this->userModel->where('phone', $phone)->first();
        if (!$user) {
            // ... (New user logic remains same, it already gets Post('password')) ...
            $password = $this->request->getPost('password');
            if (empty($password)) {
                return redirect()->back()->with('error', 'Password is required for registration.');
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $userId = $this->userModel->insert([
                'phone'         => $phone,
                'password'      => password_hash($password, PASSWORD_DEFAULT),
                'referral_code' => strtoupper(uniqid('REF')),
                'ip_address'    => $this->request->getIPAddress(),
                'is_active'     => 1,
                'last_login_at' => date('Y-m-d H:i:s'),
            ]);

            // ... wallet creation and referral processing ...
            $this->walletModel->insert(['user_id' => $userId, 'balance' => 0.00, 'coins' => 0]);
            
            $referralCode = session()->get('pending_referral') ?? $_COOKIE['refer_code'] ?? null;
            $referralService = new \App\Services\ReferralService();
            $referralService->processReferral($userId, $referralCode, $this->request->getIPAddress());

            $db->transComplete();
            $user = $this->userModel->find($userId);
        } else {
            // Existing user: Verify Password if set
            if (!empty($user['password'])) {
                $passwordInput = $this->request->getPost('password');
                if (!password_verify($passwordInput, $user['password'])) {
                    return redirect()->back()->with('error', 'Incorrect password.');
                }
            } else {
                // If user exists but has no password (legacy), allow setting it now
                $this->userModel->update($user['id'], [
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
                ]);
            }
            
            $this->userModel->update($user['id'], [
                'is_active'     => 1,
                'last_login_at' => date('Y-m-d H:i:s')
            ]);
            $userId = $user['id'];
        }

        // Set session
        $this->session->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'phone'      => $user['phone'],
            'is_admin'   => !empty($user['is_admin']) ? true : false,
            'login_time' => time(), // Track for 15-day limit
        ]);

        // Update last_login_at
        $this->userModel->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        // ==========================================
        // AUTO-APPROVE PENDING REWARDS (After 6h)
        // ==========================================
        $this->autoApproveRewards($user['id']);

        $this->session->remove('temp_phone');

        // Redirect admin to admin panel
        if (!empty($user['is_admin'])) {
            return redirect()->to(base_url('admin/'));
        }

        $redirectUrl = $this->session->get('redirect_url') ?? base_url('dashboard');
        $this->session->remove('redirect_url');

        return redirect()->to($redirectUrl);
    }

    // ==========================
    // DASHBOARD
    // ==========================
    public function index()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // Check 15-day session limit (App Simulation)
        $loginTime = $this->session->get('login_time');
        if ($loginTime && (time() - $loginTime) > (15 * 86400)) {
            $this->session->destroy();
            return redirect()->to(base_url('login'))->with('error', 'Session expired. Please login again with OTP.');
        }

        $userId = $this->session->get('user_id');
        $user   = $this->userModel->find($userId);
        $wallet = $this->walletModel->where('user_id', $userId)->first();
        
        $profileModel = new UserProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();

        // One-time auto-approval check on dashboard visit too
        $this->autoApproveRewards($userId);

        $data = [
            'user'         => $user,
            'profile'      => $profile,
            'wallet'       => $wallet,
            'referrals'    => $this->userModel->where('referred_by', $userId)->findAll(),
            'transactions' => $this->transactionModel->where('user_id', $userId)->orderBy('id', 'DESC')->findAll(10),
            'withdrawals'  => $this->withdrawalModel->where('user_id', $userId)->orderBy('id', 'DESC')->findAll(5),
        ];

        return view('user/dashboard', $data);
    }

    /**
     * Helper to approve rewards if 6+ hours have passed since registration
     */
    protected function autoApproveRewards($userId)
    {
        $transactions = $this->transactionModel
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->findAll();

        foreach ($transactions as $tx) {
            $createdTime = strtotime($tx['created_at']);
            // If 6 hours passed
            if ((time() - $createdTime) > (6 * 3600)) {
                $db = \Config\Database::connect();
                $db->transStart();

                // 1. Approve Transaction
                $this->transactionModel->update($tx['id'], ['status' => 'approved']);

                // 2. Update Wallet Balance
                $wallet = $this->walletModel->where('user_id', $userId)->first();
                if ($wallet) {
                    $newBalance = (float)$wallet['balance'] + (float)$tx['amount'];
                    $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);
                }

                // ==========================================
                // TRIGGER 500 COIN BONUS ON FIRST TRANSACTION
                // ==========================================
                // Extract origin_user_id from reference_id (e.g., REF-14-L1 or REF-14-NOREF)
                if (preg_match('/REF-(\d+)-/', $tx['reference_id'], $matches)) {
                    $originUserId = (int)$matches[1];
                    $originUser = $this->userModel->find($originUserId);
                    
                    if ($originUser && $originUser['has_done_first_tx'] == 0) {
                        $settingModel = new \App\Models\SettingModel();
                        $bonusCoins = (float) $settingModel->getVal('first_tx_coins', 500);
                        
                        $referralService = new \App\Services\ReferralService();
                        $referralService->distributeCoinRewards($originUserId, $bonusCoins);

                        // Mark origin user as done
                        $this->userModel->update($originUserId, ['has_done_first_tx' => 1]);
                    }
                }

                $db->transComplete();
            }
        }
    }

    // ==========================
    // WITHDRAW (Form)
    // ==========================
    public function withdraw()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $userId = $this->session->get('user_id');
        $wallet = $this->walletModel->where('user_id', $userId)->first();
        
        $profileModel = new UserProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();

        return view('user/withdraw', [
            'wallet'  => $wallet,
            'profile' => $profile
        ]);
    }

    // ==========================
    // SUBMIT WITHDRAWAL
    // ==========================
    public function submitWithdrawal()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        $settingModel = new \App\Models\SettingModel();
        
        // 1. Load Security Settings
        $minDays          = (int)$settingModel->getVal('withdrawal_min_days', 0);
        $minReferralCoins = (float)$settingModel->getVal('withdrawal_min_referral_coins', 0);
        $premiumReq       = (int)$settingModel->getVal('withdrawal_premium_required', 0);

        // 2. Evaluate Referral Gating Conditions
        $daysJoined = (time() - strtotime($user['created_at'])) / 86400;
        
        // Count total coins earned from referrals (REFC-)
        $totalCoinsEarned = $this->transactionModel
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->like('reference_id', 'REFC-', 'after')
            ->selectSum('amount')
            ->first()['amount'] ?? 0;

        $gatingPassed = true;
        $gatingErrors = [];

        if ($daysJoined < $minDays) {
            $gatingPassed = false;
            $gatingErrors[] = "Minimum {$minDays} days of account activity required.";
        }
        if ($totalCoinsEarned < $minReferralCoins) {
            $gatingPassed = false;
            $gatingErrors[] = "Minimum {$minReferralCoins} referral coins earned required.";
        }
        if ($premiumReq && (int)$user['is_premium'] === 0) {
            $gatingPassed = false;
            $gatingErrors[] = "Premium membership required to withdraw referral rewards.";
        }

        // 3. Calculate Withdrawable Balance
        // We distinguish between "Deposited Funds" and "Referral/Reward Earnings"
        $referralRupeeEarnings = $this->transactionModel
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->groupStart()
                ->like('reference_id', 'REF-', 'after')
                ->orLike('reference_id', 'REFR-', 'after')
                ->orLike('reference_id', 'REDEEM-', 'after')
            ->groupEnd()
            ->selectSum('amount')
            ->first()['amount'] ?? 0;

        $wallet = $this->walletModel->where('user_id', $userId)->first();
        $currentBalance = (float)($wallet['balance'] ?? 0);
        
        // If gating is NOT passed, we restrict the referral potion of the balance
        $restrictedAmount = $gatingPassed ? 0 : $referralRupeeEarnings;
        $withdrawable = max(0, $currentBalance - $restrictedAmount);

        $amount = (float) $this->request->getPost('amount');
        $details = $this->request->getPost('payment_details');

        if ($amount > $currentBalance) {
            return redirect()->back()->with('error', 'Insufficient balance.');
        }

        if ($amount > $withdrawable && !$gatingPassed) {
            $errorMsg = implode(' ', $gatingErrors);
            return redirect()->back()->with('error', "Withdrawal Restricted: {$errorMsg} (You can withdraw up to ₹" . number_format($withdrawable, 2) . " from your deposited/welcome funds).");
        }

        $amount = (float) $this->request->getPost('amount');
        $details = $this->request->getPost('payment_details');

        $wallet = $this->walletModel->where('user_id', $userId)->first();
        if (!$wallet || $wallet['balance'] < $amount) {
            return redirect()->back()->with('error', 'Insufficient balance');
        }

        if ($amount < 100) {
            return redirect()->back()->with('error', 'Minimum withdrawal is ₹100');
        }

        $this->withdrawalModel->insert([
            'user_id'         => $userId,
            'amount'          => $amount,
            'payment_details' => $details,
            'status'          => 'pending',
        ]);

        return redirect()->to(base_url('dashboard'))->with('success', 'Withdrawal request submitted!');
    }

    // ==========================
    // LOGOUT
    // ==========================
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('login'));
    }

    // ==========================
    // REDEEM COINS
    // ==========================
    public function redeemCoins()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        // Guard: Premium Required
        if (!$user || $user['is_premium'] == 0) {
            return redirect()->back()->with('error', 'Coin redemption is a Premium feature. Please upgrade.');
        }

        $coinsToRedeem = (float) $this->request->getPost('coins');

        $settingModel = new \App\Models\SettingModel();
        $minRedemption = (float) $settingModel->getVal('min_redemption', 20);
        $coinValue = (float) $settingModel->getVal('coin_value', 1.00);

        if ($coinsToRedeem < $minRedemption) {
            return redirect()->back()->with('error', "Minimum redemption is {$minRedemption} coins.");
        }

        $wallet = $this->walletModel->where('user_id', $userId)->first();
        if (!$wallet || $wallet['coins'] < $coinsToRedeem) {
            return redirect()->back()->with('error', 'Insufficient coins');
        }

        $amountToCredit = $coinsToRedeem * $coinValue;

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Update via Ledger
        $ledger = new \App\Services\LedgerService();
        
        // Deduct Coins
        $ledger->record($userId, 'debit', $coinsToRedeem, 'REDEEM-' . time(), "Redeemed for Balance", 'coins');
        
        // Add Balance
        $ledger->record($userId, 'credit', $amountToCredit, 'REDEEM-' . time(), "Redemption for {$coinsToRedeem} coins", 'balance');

        // 2. Log Transaction Record
        $this->transactionModel->insert([
            'user_id'      => $userId,
            'type'         => 'credit',
            'amount'       => $amountToCredit,
            'reference_id' => 'REDEEM-' . time(),
            'description'  => "Redeemed {$coinsToRedeem} coins for ₹{$amountToCredit}",
            'status'       => 'approved',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Redemption failed. Please try again.');
        }

        return redirect()->to(base_url('dashboard'))->with('success', "Success! You received ₹{$amountToCredit} in your wallet.");
    }

    // ==========================
    // UPGRADE TO PREMIUM
    // ==========================
    public function upgradeToPremium()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $userId = $this->session->get('user_id');
        $type = $this->request->getPost('payment_type'); // 'wallet', 'coins', or 'razorpay'

        $settingModel = new \App\Models\SettingModel();
        $priceWallet = (float) $settingModel->getVal('premium_price_wallet', 200);
        $priceCoins = (float) $settingModel->getVal('premium_price_coins', 1000);

        $wallet = $this->walletModel->where('user_id', $userId)->first();
        if (!$wallet) {
            return redirect()->back()->with('error', 'Wallet not found.');
        }

        if ($type === 'razorpay') {
            $razorpay = new RazorpayService();
            $order = $razorpay->createOrder($priceWallet, 'ORD-PRM-' . time(), [
                'user_id' => $userId,
                'type'    => 'premium_upgrade'
            ]);

            if (!$order) {
                return redirect()->back()->with('error', 'Failed to initiate Razorpay payment. Please try again.');
            }

            // Create a pending order so webhooks can find it if the user closes the tab
            $orderModel = new \App\Models\MembershipOrderModel();
            $orderModel->insert([
                'user_id'           => $userId,
                'payment_type'      => 'razorpay',
                'amount'            => $priceWallet,
                'razorpay_order_id' => $order['id'],
                'status'            => 'pending',
                'notes'             => 'Initiated Razorpay checkout'
            ]);

            return $this->response->setJSON([
                'status'   => 'success',
                'success'  => true,
                'order_id' => $order['id'],
                'amount'   => $order['amount'],
                'key_id'   => $razorpay->getKeyId(),
                'user'     => [
                    'phone' => $this->session->get('phone')
                ]
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        if ($type === 'coins') {
            if ($wallet['coins'] < $priceCoins) {
                return redirect()->back()->with('error', "Insufficient coins. You need {$priceCoins} coins.");
            }
            $this->walletModel->update($wallet['id'], ['coins' => (float)$wallet['coins'] - $priceCoins]);
            $desc = "Upgraded to Premium using {$priceCoins} coins";
            $amount = $priceCoins;
        } else {
            if ($wallet['balance'] < $priceWallet) {
                return redirect()->back()->with('error', "Insufficient wallet balance. You need ₹{$priceWallet}.");
            }
            $this->walletModel->update($wallet['id'], ['balance' => (float)$wallet['balance'] - $priceWallet]);
            $desc = "Upgraded to Premium using ₹{$priceWallet} from wallet";
            $amount = $priceWallet;
        }

        // Create Membership Order
        $orderModel = new \App\Models\MembershipOrderModel();
        $orderModel->insert([
            'user_id'      => $userId,
            'payment_type' => $type,
            'amount'       => $amount,
            'status'       => 'pending',
            'notes'        => $desc
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Upgrade request failed. Please try again.');
        }

        return redirect()->to(base_url('dashboard'))->with('success', 'Upgrade request submitted! Admin will verify and activate your Premium membership shortly.');
    }

    /**
     * Verify Razorpay Payment Callback
     */
    public function verifyRazorpayPayment()
    {
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = $this->session->get('user_id');
        $razorpayOrderId   = $this->request->getPost('razorpay_order_id');
        $razorpayPaymentId = $this->request->getPost('razorpay_payment_id');
        $razorpaySignature = $this->request->getPost('razorpay_signature');

        $razorpay = new RazorpayService();
        $success = $razorpay->verifySignature([
            'razorpay_order_id'   => $razorpayOrderId,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_signature' => $razorpaySignature
        ]);

        if (!$success) {
            return $this->response->setJSON(['status' => 'error', 'success' => false, 'message' => 'Payment verification failed.']);
        }

        $settingModel = new \App\Models\SettingModel();
        $priceWallet = (float) $settingModel->getVal('premium_price_wallet', 200);

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Find or Create Membership Order
        $orderModel = new \App\Models\MembershipOrderModel();
        $order = $orderModel->where('razorpay_order_id', $razorpayOrderId)->first();

        if ($order) {
            $orderModel->update($order['id'], [
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
                'status'              => 'approved',
                'notes'               => 'Upgraded to Premium (Verified via Client)'
            ]);
            $orderId = $order['id'];
        } else {
            // Fallback: Create if not found (shouldn't happen with the new flow)
            $orderId = $orderModel->insert([
                'user_id'             => $userId,
                'payment_type'        => 'razorpay',
                'amount'              => $priceWallet,
                'razorpay_order_id'   => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
                'status'              => 'approved',
                'notes'               => 'Upgraded to Premium (Client Recovery)'
            ]);
        }

        // 2. Mark User as Premium
        $this->userModel->update($userId, ['is_premium' => 1]);

        // 3. Log Transaction
        $this->transactionModel->insert([
            'user_id'      => $userId,
            'type'         => 'debit',
            'amount'       => $priceWallet,
            'reference_id' => 'PRM-RPP-' . $orderId,
            'description'  => "Premium Upgrade via Razorpay (Payment ID: {$razorpayPaymentId})",
            'status'       => 'approved',
        ]);

        // 4. Trigger MLM Rewards
        $referralService = new \App\Services\ReferralService();
        $referralService->processTransactionRewards($userId, $priceWallet);

        $db->transComplete();

        if ($db->transStatus() === false) {
             return $this->response->setJSON(['status' => 'error', 'success' => false, 'message' => 'Failed to activate premium membership.']);
        }

        return $this->response->setJSON(['status' => 'success', 'success' => true, 'message' => 'Premium membership activated successfully!']);
    }

    public function tree()
    {
        return view('user/tree', [
            'title'  => 'Referral Dynamic Tree',
            'active' => 'tree'
        ]);
    }

    // ==========================
    // WALLET & COIN HISTORY
    // ==========================
    public function walletHistory()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $userId = $this->session->get('user_id');
        $ledgerModel = new \App\Models\WalletTransactionModel(); // Actually WalletTransactionModel or the ledger tables?

        $profileModel = new UserProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();

        $data = [
            'title'        => 'My Coin Journey',
            'active'       => 'dashboard',
            'wallet'       => $this->walletModel->where('user_id', $userId)->first(),
            'profile'      => $profile,
            'transactions' => $ledgerModel->where('user_id', $userId)->orderBy('id', 'DESC')->findAll(100),
        ];

        return view('user/wallet_history', $data);
    }

    // ==================================
    // Phase 9: B2C Services
    // ==================================

    public function services()
    {
        return view('user/services/index');
    }

    public function rechargePortal()
    {
        $type = $this->request->getGet('type') ?? 'mobile';
        $operatorModel = new \App\Models\ServiceRechargeOperatorModel();
        
        $operators = $operatorModel->where('is_active', 1)
                                  ->where('service_type', $type)
                                  ->findAll();

        $data = [
            'title'     => ($type === 'd2h') ? 'D2H Recharge' : 'Mobile Recharge',
            'operators' => $operators,
            'type'      => $type
        ];
        return view('user/services/recharge', $data);
    }

    public function submitRecharge()
    {
        $userId = session()->get('user_id');
        $opId   = $this->request->getPost('operator_id');
        $amount = (float)$this->request->getPost('amount');

        $operatorModel = new \App\Models\ServiceRechargeOperatorModel();
        $operator = $operatorModel->find($opId);

        if (!$operator) {
            return redirect()->back()->with('error', 'Invalid operator.');
        }

        // Calculate Coins Earned based on 3 Tiers (dynamic, set by admin)
        $coins = 0;
        if ($amount <= $operator['tier_1_max']) {
            $coins = $operator['tier_1_coins'];
        } elseif ($amount <= $operator['tier_2_max']) {
            $coins = $operator['tier_2_coins'];
        } else {
            $coins = $operator['tier_3_coins']; // tier 3 applies to amount > tier_2_max
        }

        $serviceTxModel = new \App\Models\ServiceTransactionModel();
        $serviceTxModel->insert([
            'user_id'      => $userId,
            'service_type' => 'recharge',
            'operator_id'  => $opId,
            'amount'       => $amount,
            'coins_earned' => $coins,
            'status'       => 'pending',
            'reference_id' => 'REC-' . time() . '-' . $userId
        ]);

        return redirect()->to(base_url('dashboard'))->with('success', 'Recharge request submitted successfully! Coins will be credited after success verification.');
    }

    public function ecommercePortal()
    {
        $platformModel = new \App\Models\ServiceEcommercePlatformModel();
        $data = [
            'title'     => 'Affiliate Shopping',
            'platforms' => $platformModel->where('is_active', 1)->findAll()
        ];
        return view('user/services/ecommerce', $data);
    }

    public function ecommerceRedirect($id)
    {
        $userId        = session()->get('user_id');
        $platformModel = new \App\Models\ServiceEcommercePlatformModel();
        $platform      = $platformModel->find($id);

        if (!$platform) {
            return redirect()->back()->with('error', 'Invalid platform.');
        }

        // Log the click as a pending transaction
        $serviceTxModel = new \App\Models\ServiceTransactionModel();
        $serviceTxModel->insert([
            'user_id'      => $userId,
            'service_type' => 'ecommerce',
            'platform_id'  => $id,
            'amount'       => 0, // Unknown until verified
            'coins_earned' => 0, // Set by admin later
            'status'       => 'pending',
            'reference_id' => 'ECO-' . time() . '-' . $userId
        ]);

        $redirectUrl = str_replace('[USER_ID]', $userId, $platform['affiliate_url']);
        return redirect()->to($redirectUrl);
    }
}
