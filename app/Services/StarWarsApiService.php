<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;

class StarWarsApiService
{
    public function getFilms()
    {
        $guzzleClient = new GuzzleClient();

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $body = '{"query":"{allFilms{films{title}}}"}';

        $response = $guzzleClient->request('POST', env('INBENTA_SWAPI_URL'), [
            'headers' => $headers,
            'body' => $body
        ]);

        $response = json_decode($response->getBody())->data->allFilms->films;
        $response = $this->formatResponse($response);

        return $response;
    }

    public function getCharacters()
    {
        $guzzleClient = new GuzzleClient();

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $body = '{"query":"{allPeople{people{name}}}"}';

        $response = $guzzleClient->request('POST', env('INBENTA_SWAPI_URL'), [
            'headers' => $headers,
            'body' => $body
        ]);

        $response = json_decode($response->getBody())->data->allPeople->people;
        $response = $this->formatResponse($response);

        return $response;
    }

    public function formatResponse($array)
    {
        $items = array_map(function ($value) {
            return reset($value);
        }, $array);

        shuffle($items);
        $items = array_slice($items, 0, 10);

        return $items;
    }
}
