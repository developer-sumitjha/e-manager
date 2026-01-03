<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EsewaPaymentService
{
    protected $merchantId;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantId = env('ESEWA_MERCHANT_ID');
        $this->secretKey = env('ESEWA_SECRET_KEY');
        $this->baseUrl = env('ESEWA_BASE_URL', 'https://esewa.com.np/epay');
    }

    /**
     * Initialize payment
     */
    public function initiatePayment($amount, $orderId, $successUrl, $failureUrl)
    {
        $params = [
            'amt' => $amount,
            'psc' => 0,
            'pdc' => 0,
            'txAmt' => 0,
            'tAmt' => $amount,
            'pid' => $orderId,
            'scd' => $this->merchantId,
            'su' => $successUrl,
            'fu' => $failureUrl,
        ];

        return [
            'url' => $this->baseUrl . '/main',
            'params' => $params
        ];
    }

    /**
     * Verify payment
     */
    public function verifyPayment($orderId, $refId, $amount)
    {
        try {
            $verifyUrl = $this->baseUrl . '/transrec';
            
            $params = [
                'amt' => $amount,
                'rid' => $refId,
                'pid' => $orderId,
                'scd' => $this->merchantId,
            ];

            $response = Http::get($verifyUrl, $params);
            
            Log::info('eSewa verification response', [
                'order_id' => $orderId,
                'response' => $response->body()
            ]);

            // eSewa returns XML response
            $xml = simplexml_load_string($response->body());
            
            if ($xml && isset($xml->response_code) && $xml->response_code == 'Success') {
                return [
                    'success' => true,
                    'ref_id' => $refId,
                    'message' => 'Payment verified successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment verification failed'
            ];

        } catch (\Exception $e) {
            Log::error('eSewa verification error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error verifying payment: ' . $e->getMessage()
            ];
        }
    }
}







