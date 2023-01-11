<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MetricFactory extends Factory
{
    public function definition(): array
    {
        return [
            //'parent_id' => $this->faker->randomNumber(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'references' => $this->faker->word(),
            'unit_of_measurement' => $this->faker->word(),
            'study_unit' => $this->faker->word(),
            'notes' => $this->faker->text(),
            'parent_child_notes' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
