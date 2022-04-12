<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Cache;

class AuthenticationService
{
    protected $apiKey = 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=';
    protected $secret = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9qZWN0IjoieW9kYV9jaGF0Ym90X2VuIn0.anf_eerFhoNq6J8b36_qbD4VqngX79-yyBKWih_eA1-HyaMe2skiJXkRNpyWxpjmpySYWzPGncwvlwz5ZRE7eg';
    protected $conversationUrl = 'https://api-gce3.inbenta.io/prod/chatbot/v1/conversation';
    protected $authUrl = 'https://api.inbenta.io/v1/auth';

    public function getConversationToken()
    {
        return Cache::rememberForever('session_token', function () {
            $guzzleClient = new GuzzleClient();

            $headers = [
                'x-inbenta-key' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->getAuthToken()
            ];

            $response = $guzzleClient->request('POST', $this->conversationUrl, [
                'headers' => $headers
            ]);

            return json_decode($response->getBody())->sessionToken;
        });
    }

    public function getAuthToken()
    {
        if (Cache::has('access_token')) {
            return Cache::get('access_token');
        }

        $guzzleClient = new GuzzleClient();

        $headers = [
            'x-inbenta-key' => $this->apiKey,
            'Content-Type' => 'application/json'
        ];
        $body = [
            'secret' => $this->secret
        ];

        $response = $guzzleClient->request('POST', $this->authUrl, [
            'headers' => $headers,
            'body' => json_encode($body)
        ]);
        $response = json_decode($response->getBody());

        return Cache::remember('access_token', $response->expires_in, function () use ($response) {
            return $response->accessToken;
        });
    }
}
