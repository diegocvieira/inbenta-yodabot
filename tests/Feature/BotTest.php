<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BotTest extends TestCase
{
    public function test_bot_answer()
    {
        $response = $this->call('POST', route('api.conversation.message'), [
            'message' => 'Hello',
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_bot_answer_with_force_word()
    {
        $response = $this->call('POST', route('api.conversation.message'), [
            'message' => 'The force is with you?',
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($response->decodeResponseJson()['data']['message']);
    }
}
