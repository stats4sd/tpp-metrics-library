<?php

namespace Database\Factories;

use App\Models\Metric;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AltNameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'notes' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'metric_id' => Metric::factory(),
        ];
    }
}
