<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);

        if (config('app.env') === "local") {
            $this->call(TestSeeder::class);
        }

        $this->call(TopicSeeder::class);
        $this->call(DimensionSeeder::class);
        $this->call(MetricPropertySeeder::class);
    }
}
