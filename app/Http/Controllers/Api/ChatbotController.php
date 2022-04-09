<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client as GuzzleClient;
use App\Services\StarWarsApiService;
use App\Services\AuthenticationService;

class ChatbotController extends Controller
{
    protected $apiKey = 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=';
    protected $secret = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9qZWN0IjoieW9kYV9jaGF0Ym90X2VuIn0.anf_eerFhoNq6J8b36_qbD4VqngX79-yyBKWih_eA1-HyaMe2skiJXkRNpyWxpjmpySYWzPGncwvlwz5ZRE7eg';
    protected $baseEndpoint = 'https://api-gce3.inbenta.io/prod/chatbot/v1';
    protected $authEndpoint = 'https://api.inbenta.io/v1/auth';

    public function sendMessage(Request $request)
    {
        $message = $request->message;

        if (in_array('force', explode(' ', $message))) {
            return $this->getFilms();
        }

        return $this->getBotAnswer($message);
    }

    public function getBotAnswer($message)
    {
        $authenticationService = new AuthenticationService();
        $authToken = $authenticationService->getAuthToken();
        $conversationToken = $authenticationService->getConversationToken();

        $guzzleClient = new GuzzleClient();

        try {
            $response = $guzzleClient->request('POST', $this->baseEndpoint . '/conversation/message', [
                'headers' => [
                    'x-inbenta-key' => $this->apiKey,
                    'Authorization' => 'Bearer ' . $authToken,
                    'x-inbenta-session' => 'Bearer ' . $conversationToken,
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode(['message' => $message])
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 400) {
                Cache::forget('session_token');

                return response()->json([
                    'error' => 'Your session expired. Please, reload the page.'
                ], 400);
            }

             return response()->json([
                'error' => $e->getMessage()
            ]);
        }

        $responseStatusCode = $response->getStatusCode();
        $response = json_decode($response->getBody());

        if (in_array('no-results', $response->answers[0]->flags)) {
            Cache::put('no_results', Cache::has('no_results') ? 2 : 1);
        } else {
            Cache::forget('no_results');
        }

        if (Cache::has('no_results') && Cache::get('no_results') >= 2) {
            return $this->getCharacters();
        }

        return response()->json([
            'data' => $response->answers[0]->message
        ], $responseStatusCode);
    }

    public function getFilms()
    {
        $starWarsApiService = new StarWarsApiService();

        try {
            $response = $starWarsApiService->getFilms();
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], $th->getCode());
        }

        return response()->json([
            'data' => $response
        ], 200);
    }

    public function getCharacters()
    {
        $starWarsApiService = new StarWarsApiService();

        try {
            $response = $starWarsApiService->getCharacters();
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], $th->getCode());
        }

        return response()->json([
            'data' => $response
        ], 200);
    }
}
