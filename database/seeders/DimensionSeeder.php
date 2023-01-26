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

        $dimensions = [
            'Productivity (inc. stability)',
            'Animal health & welfare',
            'Synergy/positive interactions',
            'Profitability & returns',
            'Economic diversity & resilience',
            'Resource use efficiency (inc. recycling)',
            'Human health',
            'Food security & nutrition',
            'Participation & agency',
            'Fairness & equity',
            'Responsible governance',
            'Connectivity & local economy',
            'Social values & diets',
            'Soil health',
            'Water resources',
            'Air & climate',
            'Biodiversity',
        ];


        foreach($dimensions as $dimension) {
            Dimension::create([
                'name' => $dimension,
                'definition' => $definition . $dimension,
                'notes' => $notes,
            ]);
        }
    }
}
