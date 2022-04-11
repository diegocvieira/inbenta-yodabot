<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatTest extends TestCase
{
    public function test_chat_screen_can_be_rendered()
    {
        $response = $this->get(route('chat'));

        $response->assertStatus(200);
    }
}
