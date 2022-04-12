<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Cache;

class AuthenticationService
{
    public function getConversationToken()
    {
        return Cache::rememberForever('session_token', function () {
            $guzzleClient = new GuzzleClient();

            $headers = [
                'x-inbenta-key' => env('INBENTA_API_KEY'),
                'Authorization' => 'Bearer ' . $this->getAuthToken()
            ];

            $response = $guzzleClient->request('POST', env('INBENTA_CONVERSATION_URL'), [
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
            'x-inbenta-key' => env('INBENTA_API_KEY'),
            'Content-Type' => 'application/json'
        ];
        $body = [
            'secret' => env('INBENTA_SECRET')
        ];

        $response = $guzzleClient->request('POST', env('INBENTA_AUTH_URL'), [
            'headers' => $headers,
            'body' => json_encode($body)
        ]);
        $response = json_decode($response->getBody());

        return Cache::remember('access_token', $response->expires_in, function () use ($response) {
            return $response->accessToken;
        });
    }
}
