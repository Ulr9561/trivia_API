<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_post_user(): void{
        $response = $this->post('api/auth/register', [
            'name' => 'Test User 1235',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
    }

    public function test_user_login(): void{
        $response = $this->post('api/auth/login', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'jwt'
        ]);
    }
}
