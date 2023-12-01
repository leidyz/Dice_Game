<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@hotmail.com',
            'email_verified_at' => now(),
            'password' => '$hsdfsdfgs123',
        ]);

        $user->assignRole('admin', 'player');
    }
}
