<?php

namespace App\Console\Commands\OneTimeScripts;

use Illuminate\Console\Command;

class AddMetricRelationships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-metric-relationships';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add relationships between metric and entities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');

        $metrics = \App\Models\Metric::all();

        $pivotArray = ['needs_review' => true];

        // In referencables table, referencable_types are:
        // 1. App\Models\CollectionMethod
        // 2. App\Models\Dimension
        // 3. App\Models\Geography
        // 4. App\Models\Scale
        // 5. App\Models\Tool
        // 6. App\Models\Metric

        // I will handle the first five referencable_types

        foreach ($metrics as $metric) {
            $this->info('processing metric: ' . $metric->title);
            $references = $metric->references;

            foreach ($references as $reference) {

                // collection methods
                $collectionMethods = $reference->collectionMethods;
                $this->comment('Adding ' . count($collectionMethods) . ' records for metric_collection_method');

                foreach ($collectionMethods as $collectionMethod) {
                    $metric->collectionMethods()->attach([$collectionMethod->id => $pivotArray]);
                }

                // dimensions
                $dimensions = $reference->dimensions;
                $this->comment('Adding ' . count($dimensions) . ' records for metric_dimension');

                foreach ($dimensions as $dimension) {
                    $metric->dimensions()->attach([$dimension->id => $pivotArray]);
                }

                // geographies
                $geographies = $reference->geographies;
                $this->comment('Adding ' . count($geographies) . ' records for metric_geography');

                foreach ($geographies as $geography) {
                    $metric->geographies()->attach([$geography->id => $pivotArray]);
                }

                // scales
                $scales = $reference->scales;
                $this->comment('Adding ' . count($scales) . ' records for metric_scale');

                foreach ($scales as $scale) {
                    // Question: we do not know $scale type is decision, measurement or reporting
                    // metric_scale record is added without value in type column
                    // $this->comment($scale);
                    $metric->scaleDecision()->attach([$scale->id => $pivotArray]);
                }

                // tools
                $tools = $reference->tools;
                $this->comment('Adding ' . count($tools) . ' records for metric_tool');

                foreach ($tools as $tool) {
                    $metric->tools()->attach([$tool->id => $pivotArray]);
                }
            }
        }


        $this->info('done!');
    }
}
