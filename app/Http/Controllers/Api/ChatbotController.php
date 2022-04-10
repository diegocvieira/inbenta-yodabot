<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\StarWarsApiService;
use App\Services\ChatbotService;

class ChatbotController extends Controller
{
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
        $chatbotService = new ChatbotService();

        try {
            $response = $chatbotService->getAnswer($message);
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

        $response = json_decode($response->getBody());

        if (in_array('no-results', $response->answers[0]->flags)) {
            Cache::increment('no_results');
        } else {
            Cache::forget('no_results');
        }

        if (Cache::has('no_results') && Cache::get('no_results') >= 2) {
            return $this->getCharacters();
        }

        return response()->json([
            'data' => $response->answers[0]->message
        ], 200);
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
