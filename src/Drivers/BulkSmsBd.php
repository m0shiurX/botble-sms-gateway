<?php

namespace FriendsOfBotble\Sms\Drivers;

use Botble\Base\Forms\FormAbstract;
use FriendsOfBotble\Sms\DataTransferObjects\SmsResponse;
use FriendsOfBotble\Sms\Facades\Sms;
use FriendsOfBotble\Sms\Forms\BulkSmsBdGatewayForm;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BulkSmsBd extends AbstractDriver
{
    protected function performSend(string $to, string $message): SmsResponse
    {
        $apiKey = Sms::getSetting('api_key', 'bulk_sms_bd');
        $senderId = Sms::getSetting('senderid', 'bulk_sms_bd');

        if (empty($apiKey) || empty($senderId)) {
            return new SmsResponse(success: false, response: ['error' => 'BulkSMSBD credentials not configured']);
        }

        $client = new Client([
            'timeout' => 30,
            'verify' => false,
        ]);

        try {
            $response = $client->post('http://bulksmsbd.net/api/smsapi', [
                'form_params' => [
                    'api_key' => $apiKey,
                    'senderid' => $senderId,
                    'number' => $to,
                    'message' => $message,
                ],
            ]);

            $responseBody = (string) $response->getBody();
            $statusCode = $response->getStatusCode();

            // Try to parse JSON response
            $jsonResponse = json_decode($responseBody, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($jsonResponse)) {
                // BulkSMSBD returns response_code 202 for success
                $success = $statusCode === 200 &&
                    isset($jsonResponse['response_code']) &&
                    $jsonResponse['response_code'] === 202 &&
                    (empty($jsonResponse['error_message']) || $jsonResponse['error_message'] === '');

                $messageId = $jsonResponse['message_id'] ?? $jsonResponse['messageid'] ?? null;

                return new SmsResponse(
                    success: $success,
                    messageId: (string) $messageId,
                    response: $jsonResponse,
                );
            }

            // Handle non-JSON response (plain text)
            $success = $statusCode === 200 && ! str_contains(strtolower($responseBody), 'error');

            return new SmsResponse(
                success: $success,
                messageId: null,
                response: ['raw_response' => $responseBody, 'status_code' => $statusCode],
            );
        } catch (GuzzleException $e) {
            return new SmsResponse(
                success: false,
                response: [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                ],
            );
        }
    }

    public function normalizePhoneNumber(string $phone): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // If phone starts with 0, replace with 880
        if (str_starts_with($phone, '0')) {
            $phone = '880' . substr($phone, 1);
        }

        // If phone doesn't start with country code, add 880
        if (! str_starts_with($phone, '880')) {
            $phone = '880' . $phone;
        }

        return $phone;
    }

    public function getLogo(): string
    {
        return asset('vendor/core/plugins/fob-sms-gateway/images/bulksmsbd.png');
    }

    public function getInstructions(): string
    {
        return view('plugins/fob-sms-gateway::instructions.bulksmsbd');
    }

    public function getSettingForm(): FormAbstract
    {
        return BulkSmsBdGatewayForm::create();
    }
}
