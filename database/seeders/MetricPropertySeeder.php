<?php

namespace Database\Seeders;

use App\Models\MetricProperty;
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

        MetricProperty::create([
            'name' => 'Metric class',
            'definition' => 'Whether the metric is quantitative or qualitative',
            'notes' => 'to review',
            'editable_options' => true,
        ])->metricPropertyOptions()->createMany([
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


        MetricProperty::create([
            'name' => 'Metric type',
            'definition' => 'Whether the metric is performance-based or practice-based',
            'notes' => 'to review',
            'editable_options' => false,
        ])->metricPropertyOptions()->createMany([
            [
                'name' => 'performance-based'
            ],
            [
                'name' => 'practice-based',
            ],
        ]);


        MetricProperty::create([
            'name' => 'Sensitivity to change',
            'definition' => 'How sensitive is the metric to system change? ',
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
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


        MetricProperty::create([
            'name' => 'Localisable',
            'definition' => 'Is the metrics widely applicable across systems/geographies, or does it need to be adapted to the local context?',
            'editable_options' => true,
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
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


        MetricProperty::create([
            'name' => 'Participatory',
            'definition' => 'Opportunity for the metric to be measured by farmers without extensive training or equipment considering all collection methods available',
            'editable_options' => false,
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
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


        MetricProperty::create([
            'name' => 'Local knowledge equivalents',
            'definition' => 'Do local knowledge equivalents exist?',
            'editable_options' => false,
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
            [
                'name' => 'yes',
            ],
            [
                'name' => 'unknown',
            ],
        ]);


        MetricProperty::create([
            'name' => 'Ease of communication', 'definition' => 'Potential for the metric be interpreted by farmers without extensive training',
            'editable_options' => false,
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
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


        MetricProperty::create([
            'name' => 'Integrability',
            'definition' => 'What existing monitoring systems/approaches can the metric be easily integrated with?',
            'editable_options' => true,
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
            [
                'name' => 'Routine Soil Test',
            ],
            [
                'name' => 'Remote-sensing assessment',
            ],
        ]);


        MetricProperty::create([
            'name' => 'Validity',
            'definition' => 'Is the metric a strong or weak measure of the dimension?',
            'editable_options' => false,
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
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


        MetricProperty::create([
            'name' => 'Relevance to Agroecology',
            'definition' => 'Is the metric highly relevant to agroecological systems?',
            'editable_options' => false,
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
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


        MetricProperty::create([
            'name' => 'Validation',
            'definition' => 'Is the metric well evidenced/supported? Does it have
proven reliability and evidence of its use?',
            'editable_options' => true,
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
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


        MetricProperty::create([
            'name' => 'Value added',
            'definition' => 'Does it capture cross cutting performance/system
level performance?',
            'editable_options' => false,
            'notes' => 'to review',
        ])->metricPropertyOptions()->createMany([
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


    }
}
