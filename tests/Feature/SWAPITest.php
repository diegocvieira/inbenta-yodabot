<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SWAPITest extends TestCase
{
    public function test_characters_response_is_array()
    {
        $response = $this->call('GET', route('api.conversation.characters'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($response->decodeResponseJson()['data']['message']);
    }

    public function test_films_response_is_array()
    {
        $response = $this->call('GET', route('api.conversation.films'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($response->decodeResponseJson()['data']['message']);
    }
}
