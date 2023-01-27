<?php

namespace Database\Seeders;

use App\Models\CollectionMethod;
use App\Models\Property;
use Illuminate\Database\Seeder;

class CollectionPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Property::create([
            'name' => 'Equipment Needed',
            'definition' => 'What equipment is needed.',
            'notes' => 'to review',
            'editable_options' => false,
            'default_type' => CollectionMethod::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'none',
            ],
            [
                'name' => 'low',
            ],
            [
                'name' => 'medium'
            ],
            [
                'name' => 'high'
            ],

            [
                'name' => 'very high'
            ],
        ]);

        Property::create([
            'name' => 'Expertise (Collection)',
            'definition' => 'What expertise / training is needed? Can anyone collect it or does it require expertise?',
            'notes' => 'to review',
            'editable_options' => false,
            'default_type' => CollectionMethod::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'none',
            ],
            [
                'name' => 'low',
            ],
            [
                'name' => 'medium'
            ],
            [
                'name' => 'high'
            ],

            [
                'name' => 'very high'
            ],
        ]);

        Property::create([
            'name' => 'Expertise (Analysis)',
            'definition' => 'What expertise/training is needed? Can anyone collect it or does it require expertise?',
            'notes' => 'to review',
            'editable_options' => false,
            'default_type' => CollectionMethod::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'none',
            ],
            [
                'name' => 'low',
            ],
            [
                'name' => 'medium'
            ],
            [
                'name' => 'high'
            ],

            [
                'name' => 'very high'
            ],
        ]);

        Property::create([
            'name' => 'Cost',
            'definition' => 'Any information on minimal costs. Is it rapid or time consuming to collect / analyse? Average / range of cost or relative cost to similar metrics?',
            'notes' => 'to review',
            'editable_options' => false,
            'default_type' => CollectionMethod::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'none',
            ],
            [
                'name' => 'low',
            ],
            [
                'name' => 'medium'
            ],
            [
                'name' => 'high'
            ],

            [
                'name' => 'very high'
            ],
        ]);

        Property::create([
            'name' => 'Time to collect',
            'definition' => 'Any information of time requirements',
            'notes' => 'to review',
            'editable_options' => true,
            'select_multiple' => true,
            'default_type' => CollectionMethod::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'minutes',
            ],
            [
                'name' => 'hours',
            ],
            [
                'name' => 'days'
            ],
            [
                'name' => 'months'
            ],
            [
                'name' => 'years',
            ],
        ]);

        Property::create([
            'name' => 'Sampling',
            'definition' => 'Common sampling methods',
            'notes' => 'to review',
            'free_text' => true,
            'default_type' => CollectionMethod::class,
        ]);

        Property::create([
            'name' => 'Timing of sampling',
            'definition' => 'When to take samples',
            'notes' => 'to review',
            'free_text' => true,
            'default_type' => CollectionMethod::class,
        ]);

        Property::create([
            'name' => 'Frequency of collection',
            'definition' => 'Common frequency of collection (this will also be influenced by responsivity)',
            'notes' => 'to review',
            'editable_options' => true,
            'default_type' => CollectionMethod::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'hours',
            ],
            [
                'name' => 'days'
            ],
            [
                'name' => 'months'
            ],
            [
                'name' => 'years',
            ],
        ]);

    }
}
