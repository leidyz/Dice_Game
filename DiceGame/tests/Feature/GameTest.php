<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
    }
    public function testPlay()
    {
        $user = User::factory()->create();
        $user->assignRole('player');
        $this->actingAs($user, 'api');

        $response = $this->json('POST', '/api/players/' . $user->id . '/games');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'welcome',
                'dice1',
                'dice2',
                'total',
                'result',
                'status',
                'message',
                'game' => ['id', 'dice1', 'dice2', 'isWon', 'user_id', 'created_at', 'updated_at'],
            ]);
    }



    public function testIndex(){
        $user = User::factory()->create();
        $user->assignRole('player');
        $games = Game::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user,'api')->getJson('/api/players/{$user->id}/games');

        $response->assertStatus(200);
        $response->assertJson($games->toArray());
    }


    public function testDestroy(){
        $user = User::factory()->create();
        $user->assignRole('player');
        $games = Game::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user,'api')->deleteJson('/api/players/{$user->id}/games/');

        $response->assertStatus(200);
        $response->assertJson(['message'=> 'All dice rolls deleted successfully!']);

    }

}
