<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MetricPropertyOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
<<<<<<< HEAD
            'name' => $this->faker->word(),
=======
            'label' => $this->faker->word(),
>>>>>>> main
            'notes' => $this->faker->paragraph(),
        ];
    }
}
