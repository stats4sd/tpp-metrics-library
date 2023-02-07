<?php

namespace Database\Seeders;

use App\Models\Metric;
use App\Models\Property;
use Illuminate\Database\Seeder;

class MetricPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Property::create([
            'code' => '3.a',
            'name' => 'Metric class',
            'definition' => 'Whether the metric is quantitative or qualitative',
            'notes' => 'to review',
            'select_options' => true,
            'free_text' => false,
            'editable_options' => true,
            'default_type' => Metric::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'quantitative (measured)',
            ],
            [
                'name' => 'quantitative (enumerator/farmer estimated)',
            ],
            [
                'name' => 'quantitative (predicted)'
            ],
            [
                'name' => 'qualitative'
            ],
        ]);


        Property::create([
            'code' => '3.b',
            'name' => 'Metric type',
            'definition' => 'Whether the metric is performance-based or practice-based',
            'notes' => 'to review',
            'select_options' => true,
            'free_text' => false,
            'editable_options' => false,
            'default_type' => Metric::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'performance-based'
            ],
            [
                'name' => 'practice-based',
            ],
        ]);


        Property::create([
            'code' => '3.c',
            'name' => 'Sensitivity to change',
            'definition' => 'How sensitive is the metric to system change? ',
            'notes' => 'to review',
            'select_options' => true,
            'free_text' => true,
            'default_type' => Metric::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'hours',
            ],
            [
                'name' => 'days',
            ],
            [
                'name' => 'months',
            ],
            [
                'name' => 'years',
            ],
        ]);


        Property::create([
            'code' => '3.d',
            'name' => 'Localisable',
            'definition' => 'Is the metrics widely applicable across systems/geographies, or does it need to be adapted to the local context?',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => true,
            'default_type' => Metric::class,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'widely applicable',
            ],
            [
                'name' => 'can be adapted',
            ],
            [
                'name' => 'specific',
            ],
        ]);


        Property::create([
            'code' => '3.e',
            'name' => 'Participatory',
            'definition' => 'Opportunity for the metric to be measured by farmers without extensive training or equipment considering all collection methods available',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => false,
            'default_type' => Metric::class,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'none',
            ],
            [
                'name' => 'low',
            ],
            [
                'name' => 'medium',
            ],
            [
                'name' => 'high',
            ],
            [
                'name' => 'very high',
            ],
        ]);


        Property::create([
            'code' => '3.f',
            'name' => 'Local knowledge equivalents',
            'definition' => 'Do local knowledge equivalents exist?',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => false,
            'default_type' => Metric::class,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'yes',
            ],
            [
                'name' => 'unknown',
            ],
        ]);


        Property::create([
            'code' => '3.g',
            'name' => 'Ease of communication', 'definition' => 'Potential for the metric be interpreted by farmers without extensive training',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => false,
            'default_type' => Metric::class,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'very difficult',
            ],
            [
                'name' => 'difficult',
            ],
            [
                'name' => 'relatively easy',
            ],
            [
                'name' => 'easy',
            ],
            [
                'name' => 'very easy',
            ],
        ]);


        Property::create([
            'code' => '3.h',
            'name' => 'Integrability',
            'definition' => 'What existing monitoring systems/approaches can the metric be easily integrated with?',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => true,
            'default_type' => Metric::class,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'Routine Soil Test',
            ],
            [
                'name' => 'Remote-sensing assessment',
            ],
        ]);


        Property::create([
            'code' => '3.i',
            'name' => 'Validity',
            'definition' => 'Is the metric a strong or weak measure of the dimension?',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => false,
            'default_type' => Metric::class,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'none',
            ],
            [
                'name' => 'low',
            ],
            [
                'name' => 'medium',
            ],
            [
                'name' => 'high',
            ],
            [
                'name' => 'very high',
            ],
        ]);


        Property::create([
            'code' => '3.j',
            'name' => 'Relevance to Agroecology',
            'definition' => 'Is the metric highly relevant to agroecological systems?',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => false,
            'default_type' => Metric::class,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'irrelevant',
            ],
            [
                'name' => 'weak relation',
            ],
            [
                'name' => 'relevant',
            ],
            [
                'name' => 'strong relation',
            ],
            [
                'name' => 'very strong relation',
            ],

        ]);


        Property::create([
            'code' => '3.k',
            'name' => 'Validation',
            'definition' => 'Is the metric well evidenced/supported? Does it have proven reliability and evidence of its use?',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => true,
            'default_type' => Metric::class,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'proposed',
            ],
            [
                'name' => 'tested',
            ],
            [
                'name' => 'widely used',
            ],
        ]);;


        Property::create([
            'code' => '3.l',
            'name' => 'Value added',
            'definition' => 'Does it capture cross cutting performance/system level performance?',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => false,
            'default_type' => Metric::class,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'none',
            ],
            [
                'name' => 'low',
            ],
            [
                'name' => 'medium',
            ],
            [
                'name' => 'high',
            ],
            [
                'name' => 'very high',
            ],
        ]);

        Property::create([
            'code' => '4.b.',
            'name' => 'Data Availability',
            'definition' => 'Is the data required widely available in public databases? Is it regularly collected as part of routine surveys and monitoring? Is it available at the resolution needed/ that is useful?',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => true,
            'default_type' => Metric::class,
            'select_multiple' => false,
            'notes' => 'to review',
        ])->propertyOptions()->createMany([
            [
                'name' => 'unknown',
            ],
            [
                'name' => 'for specific locations',
            ],
            [
                'name' => 'widely available',
            ],
        ]);

        Property::create([
            'code' => '5.a',
            'name' => 'Advantages and Limitations',
            'definition' => 'Advantages / limitations to this metric (irrespective of collection methods)',
            'select_options' => false,
            'free_text' => true,
            'default_type' => Metric::class,
        ]);

        Property::create([
            'code' => '5.b',
            'name' => 'Methods of Computation',
            'definition' => 'How is the metric computed from raw data?',
            'select_options' => false,
            'free_text' => true,
            'default_type' => Metric::class,
        ]);

        Property::create([
            'code' => '5.c',
            'name' => 'Scalability',
            'definition' => 'Whether the quantity scales. Can you estimate the value of a larger area from constituent parts, and if so, how?',
            'select_options' => true,
            'free_text' => true,
            'editable_options' => false,
            'select_multiple' => false,
            'default_type' => Metric::class,
        ])->propertyOptions()->createMany([
            [
                'name' => 'yes',
            ],
            [
                'name' => 'no',
            ],
        ]);


    }
}
