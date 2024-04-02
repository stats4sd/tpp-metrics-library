<?php

namespace App\Console\Commands;

use App\Models\Dimension;
use App\Models\Metric;
use Illuminate\Console\Command;

class AddMetaphoneToEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-metaphone-to-entities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add metaphone result for entity name to ease duplicate detection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');

        // $this->addMetaphone(CollectionMethod::class, 'title');
        $this->addMetaphone(Dimension::class, 'name');
        //        $this->addMetaphone(Scale::class, 'name');
        //        $this->addMetaphone(Tool::class, 'name');
        //        $this->addMetaphone(Framework::class, 'name');
        //        $this->addMetaphone(Unit::class, 'name');
        //        $this->addMetaphone(MetricUser::class, 'name');
        //        $this->addMetaphone(Geography::class, 'name');
        //        $this->addMetaphone(Theme::class, 'name');

        $this->info('done!');
    }

    /**
     * @param Metric $metric
     * @return void
     */
    protected function addMetaphone($entityModel, $columnName): void
    {
        $entities = $entityModel::all();

        $this->comment('Handling ' . count($entities) . ' ' . class_basename($entityModel) . ' entities...');

        foreach ($entities as $entity) {
            // by default, metaphone returns phonemes for full string.
            // There will be very few possible duplicates detected.
            //
            // To finetune it, specify number of maximum phonemes to be returned.
            // String with same spelling at forefront will be considered as possible duplicates.
            //
            // P.S. The number of maximum phonemes can be further finetuned.

            $entity->metaphone = metaphone($entity->$columnName, 12);
            $entity->save();
        }
    }
}
