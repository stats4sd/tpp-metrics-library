<?php

namespace App\Console\Commands\OneTimeScripts;

use App\Models\CollectionMethod;
use App\Models\Metric;
use Illuminate\Console\Command;

class RemoveDuplicateLinkTableEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-duplicate-link-table-entries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Searches through all link tables and ensures that there are no model entries linked together more than once.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Pretty sure this is only for metric_ relationships.
        $metrics = Metric::all();

        $metrics->each(function (Metric $metric) {

            if ($metric->collectionMethods()->count() > 0) {

                $this->comment('----');
                $this->info('metric ID' . $metric->id);

                $collectionMethods = $metric->collectionMethods->mapWithKeys(function(CollectionMethod $collectionMethod) {
                    return [$collectionMethod->id => [
                        'relation_notes' => $collectionMethod->pivot->relation_notes,
                        'needs_review' => $collectionMethod->pivot->needs_review
                    ]];
                });

                $metric->collectionMethods()->detach($metric->collectionMethods->pluck('id')->toArray());
                $metric->collectionMethods()->sync($collectionMethods);

            }

            // $metric->collectionMethods()->sync($metric->collectionMethods->unique('id')->pluck('id'));
        });


    }
}
