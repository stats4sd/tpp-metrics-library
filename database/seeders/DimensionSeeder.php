<?php

namespace Database\Seeders;

use App\Models\Dimension;
use Illuminate\Database\Seeder;

class DimensionSeeder extends Seeder
{
    public function run(): void
    {
        $notes = 'Need to review dimension definition';
        $definition = 'Related to ';

        $dimensionTopics = [
            5 => [

                'Productivity (inc. stability)',
                'Animal health & welfare',
                'Synergy/positive interactions',
            ],
            4 => [

                'Profitability & returns',
                'Economic diversity & resilience',
                'Resource use efficiency (inc. recycling)',
            ],
            3 => [

                'Human health',
                'Food security & nutrition',
            ],
            2 => [

                'Participation & agency',
                'Fairness & equity',
                'Responsible governance',
                'Connectivity & local economy',
                'Social values & diets',
            ],
            1 => [
                'Soil health',
                'Water resources',
                'Air & climate',
                'Biodiversity',
            ]
        ];


        foreach ($dimensionTopics as $topicId => $dimensions) {

            foreach ($dimensions as $dimension)
                Dimension::create([
                    'topic_id' => $topicId,
                    'name' => $dimension,
                    'definition' => $definition . $dimension,
                    'notes' => $notes,
                ]);
        }
    }
}
