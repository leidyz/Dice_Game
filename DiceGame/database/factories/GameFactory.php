<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Game;
use App\Models\User;

class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'dice1' => $this->faker->numberBetween(1, 6),
            'dice2' => $this->faker->numberBetween(1, 6),
            'isWon' => $this->faker->boolean,
            'user_id' => User::role('player')->inRandomOrder()->first()->id,
        ];
    }
}

?>
