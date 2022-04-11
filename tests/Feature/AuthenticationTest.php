<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\AuthenticationService;

class AuthenticationTest extends TestCase
{
    public function test_auth_token_can_be_generated()
    {
        $authService = new AuthenticationService();
        $response = $authService->getAuthToken();

        $this->assertNotEmpty($response);
    }

    public function test_conversation_token_can_be_generated()
    {
        $authService = new AuthenticationService();
        $response = $authService->getConversationToken();

        $this->assertNotEmpty($response);
    }
}
