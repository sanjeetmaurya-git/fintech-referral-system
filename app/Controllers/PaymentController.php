<?php

namespace App\Controllers;

use App\Models\MembershipOrderModel;
use App\Models\UserModel;
use App\Models\WalletTransactionModel;
use App\Services\RazorpayService;
use App\Services\ReferralService;
use CodeIgniter\Controller;

class PaymentController extends BaseController
{
    /**
     * Razorpay Webhook Handler
     * This ensures payments are processed even if the user closes the browser
     */
    public function razorpayWebhook()
    {
        $razorpay = new RazorpayService();
        $webhookSecret = env('razorpay.webhook_secret');
        
        $payload = file_get_contents('php://input');
        $signature = $this->request->getServer('HTTP_X_RAZORPAY_SIGNATURE');

        if (!$razorpay->verifyWebhookSignature($payload, $signature, $webhookSecret)) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Invalid Signature']);
        }

        $data = json_decode($payload, true);
        $event = $data['event'];

        // We handle 'order.paid' to ensure the entire order is fulfilled
        if ($event === 'order.paid') {
            $orderId = $data['payload']['order']['entity']['id'];
            $paymentId = $data['payload']['payment']['entity']['id'];
            
            $this->processOrderPaid($orderId, $paymentId);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    private function processOrderPaid(string $razorpayOrderId, string $razorpayPaymentId)
    {
        $orderModel = new MembershipOrderModel();
        $userModel = new UserModel();
        $transactionModel = new WalletTransactionModel();

        // Find the order
        $order = $orderModel->where('razorpay_order_id', $razorpayOrderId)->first();

        if ($order && $order['status'] === 'pending') {
            $db = \Config\Database::connect();
            $db->transStart();

            // 1. Approve Order
            $orderModel->update($order['id'], [
                'status' => 'approved',
                'razorpay_payment_id' => $razorpayPaymentId,
                'notes' => ($order['notes'] ?? '') . ' (Verified via Webhook)'
            ]);

            // 2. Mark User as Premium
            $userModel->update($order['user_id'], ['is_premium' => 1]);

            // 3. Log Transaction (if not already logged)
            $existingTx = $transactionModel->where('reference_id', 'PRM-RPP-' . $order['id'])->first();
            if (!$existingTx) {
                $transactionModel->insert([
                    'user_id'      => $order['user_id'],
                    'type'         => 'debit',
                    'amount'       => $order['amount'],
                    'reference_id' => 'PRM-RPP-' . $order['id'],
                    'description'  => "Premium Upgrade via Razorpay Webhook (Payment ID: {$razorpayPaymentId})",
                    'status'       => 'approved',
                ]);
            }

            // 4. Trigger MLM Rewards
            $referralService = new ReferralService();
            $referralService->processTransactionRewards($order['user_id'], (float)$order['amount']);

            $db->transComplete();

            if ($db->transStatus() === true) {
                log_message('info', "[Webhook] Successfully processed order: " . $razorpayOrderId);
            }
        }
    }
}
