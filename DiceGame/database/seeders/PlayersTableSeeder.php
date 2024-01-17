<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Player 1
        $user1 = User::create([
            'name' => 'Player 1',
            'email' => 'player1@example.com',
            'password' => Hash::make('password'),
        ]);
        $user1->assignRole('player');

        // Player 2
        $user2 = User::create([
            'name' => 'Player 2',
            'email' => 'player2@example.com',
            'password' => Hash::make('password'),
        ]);
        $user2->assignRole('player');

        // Player 3
        $user3 = User::create([
            'name' => 'Player 3',
            'email' => 'player3@example.com',
            'password' => Hash::make('password'),
        ]);
        $user3->assignRole('player');
    }
}
