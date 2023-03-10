<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ToolFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'definition' => $this->faker->text(),
            'notes' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
