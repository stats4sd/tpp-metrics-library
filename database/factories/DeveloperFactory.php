<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeveloperFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'notes' => $this->faker->paragraph(),
        ];
    }
}
