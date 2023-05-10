<?php

namespace App\Jobs;

use App\Models\Scale;
use App\Models\Metric;
use App\Models\Dimension;
use App\Models\Geography;
use App\Models\Reference;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\CollectionMethod;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Reference $reference)
      {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $imported = $this->reference->imported_entities;

        $str = Str::of($imported)->split('/[\n\r]/');

        foreach($str as $line) {

            $line = Str::of($line)
                ->trim();

            $type = $line->before(":");
            $items = $line->after(':');

            $items = collect(str_getcsv($items));

            $items = $items->map(fn($item) => Str::of($item)
                ->trim()
                ->toString())
                ->filter(fn($item) => $item !== '');


            foreach ($items as $item) {

                if($type->toString() === "Metrics") {

                    $metric = Metric::updateOrCreate(['title' => $item]);
                    $this->reference->metrics()->syncWithoutDetaching([$metric->id => ['reference_type' => 'reference']]);

                }

                elseif($type->toString()=== "Dimensions") {

                    $dimension = Dimension::updateOrCreate(['name' => $item]);
                    $this->reference->dimensions()->syncWithoutDetaching([$dimension->id => ['reference_type' => 'reference']]);

                }

                elseif($type->toString()=== "Methods") {
                    $collection_method = CollectionMethod::updateOrCreate(['title' => $item]);
                    $this->reference->collectionMethods()->syncWithoutDetaching([$collection_method->id => ['reference_type' => 'reference']]);

                }

                elseif($type->toString()=== "Country/region") {
                    $geography = Geography::updateOrCreate(['name' => $item]);
                    $this->reference->geographies()->syncWithoutDetaching([$geography->id => ['reference_type' => 'reference']]);

                }

                elseif($type->toString()=== "Scale" || $type->toString()===  "Level") {
                    $scale = Scale::updateOrCreate(['name' => $item]);
                    $this->reference->scales()->syncWithoutDetaching([$scale->id => ['reference_type' => 'reference']]);

                }

            }

        }

    }
}
