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

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
        Artisan::call('passport:install');
    }


    public function testUpdateNameSuccessfully()
    {
       
        $user = User::factory()->create();
        $user->assignRole('player'); 

        $response = $this->actingAs($user, 'api')->putJson('/api/players/' . $user->id, ['newName' => 'NewName']);
 
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Player name updated successfully',
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'NewName']);
    }

}
