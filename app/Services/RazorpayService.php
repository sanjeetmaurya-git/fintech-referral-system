<?php

namespace App\Services;

use Razorpay\Api\Api;
use Exception;

class RazorpayService
{
    protected $api;
    protected $keyId;
    protected $keySecret;

    public function __construct()
    {
        $this->keyId = env('razorpay.key_id');
        $this->keySecret = env('razorpay.key_secret');

        if ($this->keyId && $this->keySecret) {
            $this->api = new Api($this->keyId, $this->keySecret);
        }
    }

    /**
     * Create a Razorpay Order
     */
    public function createOrder(float $amount, string $receiptId, array $notes = []): ?array
    {
        if (!$this->api) {
            return null;
        }

        try {
            $orderData = [
                'receipt'         => $receiptId,
                'amount'          => $amount * 100, // Amount in paise
                'currency'        => 'INR',
                'notes'           => $notes
            ];

            $order = $this->api->order->create($orderData);
            return $order->toArray();
        } catch (Exception $e) {
            log_message('error', '[RazorpayService] CreateOrder Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify Payment Signature
     */
    public function verifySignature(array $attributes): bool
    {
        if (!$this->api) {
            return false;
        }

        try {
            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (Exception $e) {
            log_message('error', '[RazorpayService] VerifySignature Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify Webhook Signature
     */
    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
    {
        if (!$this->api) {
            return false;
        }

        try {
            $this->api->utility->verifyWebhookSignature($payload, $signature, $secret);
            return true;
        } catch (Exception $e) {
            log_message('error', '[RazorpayService] VerifyWebhook Error: ' . $e->getMessage());
            return false;
        }
    }

    public function getKeyId(): ?string
    {
        return $this->keyId;
    }
}
