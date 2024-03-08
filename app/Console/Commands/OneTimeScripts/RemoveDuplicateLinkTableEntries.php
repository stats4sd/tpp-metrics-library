<?php

namespace App\Console\Commands\OneTimeScripts;

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

        $metrics = Metric::all();

        $metrics->each(function (Metric $metric) {

            if ($metric->collectionMethods()->count() > 0) {

                $this->comment('----');
                $this->info('metric ID' . $metric->id);

                $this->deduplicateRelationship($metric, 'collectionMethods');
                $this->deduplicateRelationship($metric, 'dimensions');
                $this->deduplicateRelationship($metric, 'scales');
                $this->deduplicateRelationship($metric, 'tools');
                $this->deduplicateRelationship($metric, 'frameworks');
                $this->deduplicateRelationship($metric, 'units');
                $this->deduplicateRelationship($metric, 'metricUsers');
                $this->deduplicateRelationship($metric, 'geographies');
                $this->deduplicateRelationship($metric, 'scales');

                // referencables are all unique for metrics;

            }
        });


    }

    /**
     * @param Metric $metric
     * @return void
     */
    protected function deduplicateRelationship(Metric $metric, string $relationship): void
    {

        $count = $metric->$relationship()->count();

        // This would not work if there were duplicate entries with different pivot values.
        // A manual review of the database says this is not the case, so this is fine.
        $entriesWithPivotValues = $metric->$relationship->mapWithKeys(function ($relatedEntry) {
            return [$relatedEntry->id => [
                'relation_notes' => $relatedEntry->pivot->relation_notes,
                'needs_review' => $relatedEntry->pivot->needs_review
            ]];
        });

        $metric->$relationship()->detach($metric->$relationship->pluck('id')->toArray());
        $metric->$relationship()->sync($entriesWithPivotValues);

        $newCount = $metric->$relationship()->count();

        if($count !== $newCount) {
            $this->comment('Removed ' . ($count - $newCount) . ' duplicate entries from ' . $relationship);
        }
    }
}
