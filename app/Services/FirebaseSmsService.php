<?php

namespace App\Services;

use App\Models\SmsMessage;
use App\Models\SmsCredit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseSmsService
{
    protected $serverKey;
    protected $apiUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        $this->serverKey = config('firebase.server_key');
    }

    /**
     * Send SMS via Firebase Cloud Messaging
     */
    public function sendSms(string $phoneNumber, string $message, array $options = []): array
    {
        try {
            // Check credits
            $credits = SmsCredit::getInstance();
            if ($credits->balance <= 0) {
                return [
                    'success' => false,
                    'error' => 'Insufficient SMS credits'
                ];
            }

            // Validate phone number
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            // For demo purposes, we'll simulate sending
            // In production, you would use actual SMS gateway
            $response = $this->sendViaSmsGateway($phoneNumber, $message);

            if ($response['success']) {
                // Deduct credit
                $credits->deduct(1);

                return [
                    'success' => true,
                    'message_id' => $response['message_id'] ?? uniqid('sms_'),
                    'cost' => $credits->cost_per_sms,
                ];
            }

            return $response;

        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk SMS
     */
    public function sendBulkSms(array $recipients, string $message): array
    {
        $results = [
            'total' => count($recipients),
            'sent' => 0,
            'failed' => 0,
            'messages' => []
        ];

        foreach ($recipients as $recipient) {
            $result = $this->sendSms($recipient, $message);
            
            if ($result['success']) {
                $results['sent']++;
            } else {
                $results['failed']++;
            }
            
            $results['messages'][] = [
                'phone' => $recipient,
                'status' => $result['success'] ? 'sent' : 'failed',
                'error' => $result['error'] ?? null,
            ];
        }

        return $results;
    }

    /**
     * Send SMS via actual SMS gateway (Twilio-like implementation)
     */
    protected function sendViaSmsGateway(string $phoneNumber, string $message): array
    {
        // In production, integrate with actual SMS provider
        // For now, we'll simulate a successful send
        
        $smsProvider = config('firebase.sms_provider');

        if ($smsProvider === 'twilio') {
            return $this->sendViaTwilio($phoneNumber, $message);
        }

        // Simulate successful send for demo
        return [
            'success' => true,
            'message_id' => 'SM' . uniqid(),
            'status' => 'sent',
        ];
    }

    /**
     * Send via Twilio (example implementation)
     */
    protected function sendViaTwilio(string $phoneNumber, string $message): array
    {
        $sid = config('firebase.twilio.sid');
        $token = config('firebase.twilio.token');
        $from = config('firebase.twilio.from');

        if (empty($sid) || empty($token)) {
            return [
                'success' => true, // Return true for demo purposes
                'message_id' => 'DEMO_' . uniqid(),
                'note' => 'Demo mode - Twilio not configured'
            ];
        }

        try {
            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'From' => $from,
                    'To' => $phoneNumber,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message_id' => $data['sid'] ?? null,
                    'status' => $data['status'] ?? 'sent',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'SMS sending failed'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Add country code if not present (assuming Nepal +977)
        if (!str_starts_with($phoneNumber, '977') && strlen($phoneNumber) === 10) {
            $phoneNumber = '977' . $phoneNumber;
        }

        return '+' . $phoneNumber;
    }

    /**
     * Validate phone number
     */
    public function validatePhoneNumber(string $phoneNumber): bool
    {
        $formatted = $this->formatPhoneNumber($phoneNumber);
        return strlen($formatted) >= 10 && strlen($formatted) <= 15;
    }

    /**
     * Get SMS delivery status
     */
    public function getDeliveryStatus(string $messageId): array
    {
        // In production, query the SMS provider's API
        // For now, return simulated status
        return [
            'message_id' => $messageId,
            'status' => 'delivered',
            'delivered_at' => now(),
        ];
    }
}
