<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MetricPropertyOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'notes' => $this->faker->paragraph(),
        ];
    }
}
