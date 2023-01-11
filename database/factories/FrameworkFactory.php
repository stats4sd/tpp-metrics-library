<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FrameworkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(),
            'description' => $this->faker->text(),
            'notes' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
