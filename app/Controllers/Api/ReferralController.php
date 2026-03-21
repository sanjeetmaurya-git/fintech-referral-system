<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;

class ReferralController extends BaseController
{
    protected $userModel;
    protected $walletModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->userModel        = new UserModel();
        $this->walletModel      = new WalletModel();
        $this->transactionModel = new WalletTransactionModel();
    }

    // ===================================
    // GET /api/referral/wallet/{userId}
    // ===================================
    public function walletBalance(int $userId)
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'User not found',
            ]);
        }

        $wallet = $this->walletModel->where('user_id', $userId)->first();

        return $this->response->setJSON([
            'status'  => true,
            'user_id' => $userId,
            'balance' => $wallet ? (float) $wallet['balance'] : 0.00,
        ]);
    }

    // ===================================
    // GET /api/referral/tree/{userId}
    // ===================================
    public function referralTree(int $userId)
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'User not found',
            ]);
        }

        // Direct referrals (level 1)
        $directReferrals = $this->userModel
            ->where('referred_by', $userId)
            ->select('id, phone, referral_code, created_at')
            ->findAll();

        return $this->response->setJSON([
            'status'          => true,
            'user_id'         => $userId,
            'referral_code'   => $user['referral_code'],
            'total_referrals' => count($directReferrals),
            'referred_users'  => $directReferrals,
        ]);
    }

    // ===========================================
    // GET /api/referral/transactions/{userId}
    // ===========================================
    public function transactions(int $userId)
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'User not found',
            ]);
        }

        $status = $this->request->getGet('status'); // optional filter: pending|approved|rejected

        $query = $this->transactionModel->where('user_id', $userId);

        if (!empty($status) && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query = $query->where('status', $status);
        }

        $transactions = $query->orderBy('id', 'DESC')->findAll();

        $totals = [
            'pending'  => 0.0,
            'approved' => 0.0,
            'rejected' => 0.0,
        ];

        foreach ($transactions as $tx) {
            $totals[$tx['status']] += (float) $tx['amount'];
        }

        return $this->response->setJSON([
            'status'       => true,
            'user_id'      => $userId,
            'totals'       => $totals,
            'transactions' => $transactions,
        ]);
    }

    // ===========================================
    // GET /api/referral/tree-full
    // ===========================================
    public function getRecursiveTree()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['status' => false, 'message' => 'Unauthorized']);
        }

        $user = $this->userModel->find($userId);
        
        $tree = [
            'name'      => 'You (#' . $user['id'] . ')',
            'phone'     => $user['phone'],
            'children'  => $this->buildTree($userId, 1)
        ];

        return $this->response->setJSON($tree);
    }

    private function buildTree($parentId, $level)
    {
        if ($level > 8) return [];

        $referrals = $this->userModel->where('referred_by', $parentId)->findAll();
        $branch = [];

        foreach ($referrals as $ref) {
            $branch[] = [
                'name'     => 'User #' . $ref['id'],
                'phone'    => substr($ref['phone'], 0, 4) . '****' . substr($ref['phone'], -2),
                'children' => $this->buildTree($ref['id'], $level + 1)
            ];
        }

        return $branch;
    }
}
