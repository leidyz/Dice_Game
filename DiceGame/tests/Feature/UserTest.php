<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{

    public function testUserRegistration()
    {
        $userData = [
            'name' => 'karly',
            'email' => 'karly@example.com',
            'password' => 'asdfg123',
            'password_confirmation' => 'asdfg123',
        ];

        $response = $this->postJson('/api/players', $userData);

        $response->assertJson([
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => $userData,
        ]);
        $response->assertStatus(201);
    }

    public function testUserRegistrationWithValidationErrors()
    {
        // Missing email
        $invalidUserData = [
            'name' => 'karly',
            'password' => 'asdfg123',
            'password_confirmation' => 'asdfg123',
        ];

        $response = $this->postJson('/api/players', $invalidUserData);

        $response->assertStatus(403);
        $response->assertJsonStructure(['status', 'message', 'data']);
        $response->assertJsonValidationErrors(['email']);
    }
}
