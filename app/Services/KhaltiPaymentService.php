<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KhaltiPaymentService
{
    protected $publicKey;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->publicKey = env('KHALTI_PUBLIC_KEY');
        $this->secretKey = env('KHALTI_SECRET_KEY');
        $this->baseUrl = env('KHALTI_BASE_URL', 'https://khalti.com/api/v2');
    }

    /**
     * Initiate payment
     */
    public function initiatePayment($amount, $orderId, $productName, $returnUrl)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->secretKey,
            ])->post($this->baseUrl . '/epayment/initiate/', [
                'return_url' => $returnUrl,
                'website_url' => env('APP_URL'),
                'amount' => $amount * 100, // Convert to paisa
                'purchase_order_id' => $orderId,
                'purchase_order_name' => $productName,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['pidx'])) {
                return [
                    'success' => true,
                    'payment_url' => $data['payment_url'],
                    'pidx' => $data['pidx'],
                ];
            }

            return [
                'success' => false,
                'message' => $data['detail'] ?? 'Failed to initiate payment'
            ];

        } catch (\Exception $e) {
            Log::error('Khalti initiate error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error initiating payment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify payment
     */
    public function verifyPayment($pidx)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->secretKey,
            ])->post($this->baseUrl . '/epayment/lookup/', [
                'pidx' => $pidx
            ]);

            $data = $response->json();

            Log::info('Khalti verification response', [
                'pidx' => $pidx,
                'response' => $data
            ]);

            if ($response->successful() && isset($data['status']) && $data['status'] === 'Completed') {
                return [
                    'success' => true,
                    'transaction_id' => $data['transaction_id'],
                    'amount' => $data['total_amount'] / 100, // Convert from paisa
                    'message' => 'Payment verified successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment verification failed'
            ];

        } catch (\Exception $e) {
            Log::error('Khalti verification error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error verifying payment: ' . $e->getMessage()
            ];
        }
    }
}







