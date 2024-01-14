<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LoginRegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp (): void {

        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
        Artisan::call('passport:install');

    }

    public function testRegisterUser(): void
    {
        $userData = [
            'name' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->email,
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ];

        $response = $this->postJson('/api/players', $userData);
        $response->assertJson(['message' => 'User is created successfully.']);
        $response->assertStatus(201);
    }
    public function testRegisterUserWithValidationError(): void
    {
        $userData = [
            'name' => $this->faker->unique()->userName,
            'email' => 'wrongemail',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ];

        $response = $this->postJson('/api/players', $userData);
        $response->assertJson(['message' => 'Validation Error!']);
        $response->assertStatus(403);
    }



    public function testLoginUser(){
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];
      
        $response = $this->postJson('/api/login', $loginData);
        $response->assertStatus(200);
    
    }
    public function testLoginUserWithInvalidCredentials(){
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'wrong123',
        ];
      
        $response = $this->postJson('/api/login', $loginData);
        $response->assertJson(['message' => 'Invalid credentials']);
        $response->assertStatus(401);
    }

 

    public function testLogoutUser(){
        
        $user = User::factory()->create();
        $response = $this->actingAs($user,'api')->postJson('/api/logout');
        $response->assertJson(['message' => 'User is logged out successfully']);
        $this->assertCount(0, $user->tokens);
        $response->assertStatus(200);

    }

    

 

}
