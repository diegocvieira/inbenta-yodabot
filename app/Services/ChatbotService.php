<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;
use App\Services\AuthenticationService;

class ChatbotService
{
    public function getAnswer($message)
    {
        $authService = new AuthenticationService();
        $guzzleClient = new GuzzleClient();

        $headers = [
            'x-inbenta-key' => env('INBENTA_API_KEY'),
            'Authorization' => 'Bearer ' . $authService->getAuthToken(),
            'x-inbenta-session' => 'Bearer ' . $authService->getConversationToken(),
            'Content-Type' => 'application/json'
        ];
        $body = [
            'message' => $message
        ];

        $response = $guzzleClient->request('POST', env('INBENTA_BASE_URL') . '/conversation/message', [
            'headers' => $headers,
            'body' => json_encode($body)
        ]);

        return $response;
    }
}
