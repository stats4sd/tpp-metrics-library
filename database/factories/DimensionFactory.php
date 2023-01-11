<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DimensionFactory extends Factory
{
    public function definition(): array
    {
        return [
//            'parent_id' => $this->faker->randomNumber(),
            'name' => $this->faker->words(),
            'description' => $this->faker->text(),
            'notes' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'topic_id' => Topic::factory(),
        ];
    }
}
