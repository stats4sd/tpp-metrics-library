<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'label' => $this->faker->word(),
            'notes' => $this->faker->paragraph(),
        ];
    }
}
