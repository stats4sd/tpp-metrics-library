<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{

    public function run(): void
    {
        if (Topic::count() === 0) {
            Topic::create([
                'name' => 'environment',
                'definition' => 'related to environmental factors',
                'notes' => 'Need to review definition!',
            ]);

            Topic::create([
                'name' => 'social',
                'definition' => 'related to social issues',
                'notes' => 'Need to review topic definition',
            ]);

            Topic::create([
                'name' => 'human',
                'definition' => 'related to human health and wellbeing',
                'notes' => 'Need to review topic definition.',
            ]);

            Topic::create([
                'name' => 'economic',
                'definition' => 'related to economic systems',
                'notes' => 'Need to review topic definition',
            ]);

            Topic::create([
                'name' => 'Productivity',
                'definition' => 'Related to farm outputs and agricultural system efficiency',
                'notes' => 'Need to review topic definition',
            ]);
        }
    }
}
