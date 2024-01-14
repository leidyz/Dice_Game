<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void{
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
        Artisan::call('passport:install');
    }


    public function testUpdateNameSuccessfully(){
        $user = User::factory()->create();
        $user->assignRole('player'); 

        $response = $this->actingAs($user, 'api')->putJson('/api/players/' . $user->id, ['newName' => 'NewName']);
 
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Player name updated successfully',
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'NewName']);
    }

    public function testUpdateNameWithValidationErrors(){
    
        $user = User::factory()->create();
        $user->assignRole('player'); 
        $existingUser = User::factory()->create(['name' => 'ExistingName']);
        $response = $this->actingAs($user, 'api')->putJson('/api/players/' . $user->id, ['newName' => 'ExistingName']);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => [
                    'newName',
                ],
            ]);
    }


    public function testIndex() {
        $user = User::factory()->create();
        $user->assignRole('admin'); 
        $response = $this->actingAs($user, 'api')->getJson('/api/players/');
        $response->assertStatus(200);
        $response->assertJsonStructure(['Players Average Success Rate', 'users']);
    }

    public function testGetRanking() { 
        $user = User::factory()->create();
        $user->assignRole('admin'); 
        $response = $this->actingAs($user, 'api')->getJson('/api/players/ranking');
        $response->assertStatus(200);
        $response->assertJsonStructure(['Players Ranking']);
    }

    public function testGetLoser(){
        $user = User::factory()->create();
        $user->assignRole('admin');
        $response = $this->actingAs($user,'api')->getJson('/api/players/ranking/loser');
        $response->assertStatus(200);
        $response->assertJsonStructure(['Worst Success Rate']);
    }

    public function testGetWinner(){
        $user = User::factory()->create();
        $user->assignRole('admin');
        $response = $this->actingAs($user,'api')->getJson('/api/players/ranking/winner');
        $response->assertStatus(200);
        $response->assertJsonStructure(['Best Success Rate']);
    }

    public function testPlayerCannotAccessAdminMethods() {
        $user = User::factory()->create();
        $user->assignRole('player');
        $response = $this->actingAs($user, 'api')->getJson('/api/players/ranking/loser');
        $response->assertStatus(403);
    }

    public function testAdminCannotAccessPlayerMethods(){
        $user = User::factory()->create();
        $user->assignRole('admin');
        $response = $this->actingAs($user,'api')->getJson('/api/players/{$user->id}/games/');
        $response->assertStatus(403);
    }

}
