<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;
use App\Services\AuthenticationService;

class ChatbotService
{
    protected $apiKey = 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=';
    protected $baseUrl = 'https://api-gce3.inbenta.io/prod/chatbot/v1';

    public function getAnswer($message)
    {
        $authService = new AuthenticationService();
        $guzzleClient = new GuzzleClient();

        $headers = [
            'x-inbenta-key' => $this->apiKey,
            'Authorization' => 'Bearer ' . $authService->getAuthToken(),
            'x-inbenta-session' => 'Bearer ' . $authService->getConversationToken(),
            'Content-Type' => 'application/json'
        ];
        $body = [
            'message' => $message
        ];

        $response = $guzzleClient->request('POST', $this->baseUrl . '/conversation/message', [
            'headers' => $headers,
            'body' => json_encode($body)
        ]);

        return $response;
    }
}
