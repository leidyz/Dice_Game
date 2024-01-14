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

}
