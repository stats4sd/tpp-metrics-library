<?php

namespace App\Console\Commands;

use App\Models\Dimension;
use App\Models\Metric;
use Illuminate\Console\Command;

class AddSoundexToEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-soundex-to-entities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add soundex result for entity name to ease duplicate detection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');

       // $this->addSoundex(CollectionMethod::class, 'title');
        $this->addSoundex(Dimension::class, 'name');
//        $this->addSoundex(Scale::class, 'name');
//        $this->addSoundex(Tool::class, 'name');
//        $this->addSoundex(Framework::class, 'name');
//        $this->addSoundex(Unit::class, 'name');
//        $this->addSoundex(MetricUser::class, 'name');
//        $this->addSoundex(Geography::class, 'name');
//        $this->addSoundex(Theme::class, 'name');

        $this->info('done!');
    }

    /**
     * @param Metric $metric
     * @return void
     */
    protected function addSoundex($entityModel, $columnName): void
    {
        $entities = $entityModel::all();

        $this->comment('Handling ' . count($entities) . ' ' . class_basename($entityModel) . ' entities...');

        foreach ($entities as $entity) {
            $entity->soundex = soundex($entity->$columnName);
            $entity->save();
        }
    }
}
