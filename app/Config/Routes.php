<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('test', function() { return 'CodeIgniter is alive! URI is: ' . current_url(); });
$routes->get('fintech-referral-system/public', function() { return 'Matched full path!'; });
$routes->get('join/(:any)', 'JoinController::index/$1');
$routes->post('payment/webhook', 'PaymentController::razorpayWebhook');

// ==========================
// File Serving Route
// ==========================
$routes->get('uploads/(.+)', 'FileController::serve/$1');

// ==========================
// API Routes
// ==========================
$routes->group('api', ['filter' => 'rateLimit'], function($routes) {
    // Auth
    $routes->get('/',           'Api\AuthController::index');
    $routes->post('register',   'Api\AuthController::register');
    $routes->post('verify-otp', 'Api\AuthController::verifyOtp');

    // Referral
    $routes->get('referral/wallet/(:num)',       'Api\ReferralController::walletBalance/$1');
    $routes->get('referral/tree/(:num)',         'Api\ReferralController::referralTree/$1');
    $routes->get('referral/tree-full',           'Api\ReferralController::getRecursiveTree');
    $routes->get('referral/transactions/(:num)', 'Api\ReferralController::transactions/$1');
});

// ==========================
// Admin Routes
// ==========================
$routes->group('admin', ['filter' => 'adminAuth'], function($routes) {
    $routes->get('/',                    'AdminController::index');
    $routes->get('users',                'AdminController::users');
    $routes->get('transactions',         'AdminController::transactions');
    $routes->post('approve/(:num)',      'AdminController::approveReward/$1');
    $routes->post('reject/(:num)',       'AdminController::rejectReward/$1');

    // Withdrawals
    $routes->get('withdrawals',          'AdminController::withdrawals');
    $routes->post('withdrawals/batch', 'AdminController::processBatchAction');
    $routes->post('withdrawal/approve/(:num)', 'AdminController::approveWithdrawal/$1');
    $routes->post('withdrawal/reject/(:num)',  'AdminController::rejectWithdrawal/$1');

    // Settings
    $routes->get('settings',             'AdminController::settings');
    $routes->post('settings/update',     'AdminController::updateSettings');

    // Admin Support
    $routes->get('support',             'SupportController::adminIndex');
    $routes->get('support/view/(:num)', 'SupportController::view/$1');
    $routes->post('support/reply/(:num)', 'SupportController::reply/$1');

    // Orders
    $routes->get('membership-orders',    'AdminController::membershipOrders');
    $routes->post('membership-order/approve/(:num)', 'AdminController::approveMembershipOrder/$1');
    $routes->post('membership-order/reject/(:num)',  'AdminController::rejectMembershipOrder/$1');

    // User Settings (Admin membership management)
    $routes->get('user-settings',        'AdminController::userSettings');
    $routes->post('user-settings/update-premium', 'AdminController::updateUserPremiumStatus');

    // Services (Phase 9)
    $routes->get('services', 'AdminController::manageServices');
    $routes->get('services/recharge', 'AdminController::manageRechargeOperators');
    $routes->post('services/recharge/save', 'AdminController::saveRechargeOperator');
    $routes->get('services/ecommerce', 'AdminController::manageEcommercePlatforms');
    $routes->post('services/ecommerce/save', 'AdminController::saveEcommercePlatform');
    $routes->get('services/transactions', 'AdminController::manageServiceTransactions');
    $routes->post('services/transactions/approve/(:num)', 'AdminController::approveServiceTransaction/$1');

    // Worker Management (Phase 10)
    $routes->get('workers', 'AdminController::workers');
    $routes->get('workers/view/(:num)', 'AdminController::viewWorker/$1');
    $routes->post('workers/approve/(:num)', 'AdminController::approveWorker/$1');
    $routes->post('workers/reject/(:num)', 'AdminController::rejectWorker/$1');
    $routes->post('workers/delete/(:num)', 'AdminController::deleteWorker/$1');
    $routes->post('workers/verify-document/(:num)', 'AdminController::verifyDocument/$1');
});

// ==========================
// User Web Routes
// ==========================
$routes->get('services', 'UserDashboardController::services');

$routes->get('login',            'UserDashboardController::login');
$routes->post('login/send-otp',  'UserDashboardController::sendOtp');
$routes->get('verify',           'UserDashboardController::verify');
$routes->post('login/verify',    'UserDashboardController::doVerify');

$routes->group('', ['filter' => 'auth'], function($routes) { // Assuming an 'auth' filter for authenticated routes
    $routes->get('dashboard',        'UserDashboardController::index');
    $routes->get('tree',             'UserDashboardController::tree');

    // Withdrawals
    $routes->get('withdraw',         'UserDashboardController::withdraw');
    $routes->post('withdraw/submit', 'UserDashboardController::submitWithdrawal');

    // Profile
    $routes->get('profile',          'ProfileController::index');
    $routes->post('profile/update',  'ProfileController::update');

    // Coins & Premium
    $routes->post('redeem-coins',    'UserDashboardController::redeemCoins');
    $routes->post('upgrade-premium', 'UserDashboardController::upgradeToPremium');
    $routes->post('verify-razorpay-payment', 'UserDashboardController::verifyRazorpayPayment');

    // Support
    $routes->get('support',             'SupportController::index');
    $routes->get('support/create',      'SupportController::create');
    $routes->post('support/store',      'SupportController::store');
    $routes->get('support/view/(:num)', 'SupportController::view/$1');
    $routes->post('support/reply/(:num)', 'SupportController::reply/$1');

    // Notifications
    $routes->get('notifications',       'NotificationController::index');
    $routes->post('notifications/read/(:num)', 'NotificationController::markAsRead/$1');

    // Wallet History
    $routes->get('wallet-history',      'UserDashboardController::walletHistory');

    // User Service Actions (Phase 9)
    $routes->get('services/recharge', 'UserDashboardController::rechargePortal');
    $routes->post('services/recharge/submit', 'UserDashboardController::submitRecharge');
    $routes->get('services/ecommerce', 'UserDashboardController::ecommercePortal');
    $routes->get('services/ecommerce/redirect/(:num)', 'UserDashboardController::ecommerceRedirect/$1');

    // Hiring System (Phase 2)
    $routes->get('hire', 'WorkerController::listCategories');
    $routes->get('hire/workers/(:num)', 'WorkerController::listWorkers/$1');
    $routes->get('hire/details/(:num)', 'WorkerController::details/$1');
    $routes->post('hire/request', 'WorkerController::hireRequest');

    // Worker Registration (Protected)
    $routes->get('worker/register', 'WorkerController::register');
    $routes->post('worker/store', 'WorkerController::store');
    $routes->get('worker/subcategories/(:num)', 'WorkerController::getSubcategories/$1');
    $routes->get('worker/success', 'WorkerController::success');
    $routes->get('worker/dashboard', 'WorkerController::dashboard');
    $routes->get('worker/toggle-status', 'WorkerController::toggleStatus');
});

$routes->get('logout', 'UserDashboardController::logout');


