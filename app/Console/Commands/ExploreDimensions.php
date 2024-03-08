<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExploreDimensions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:explore-dimensions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exploring dimensions and their relationships with metrics/themes/references';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dimensions = \App\Models\Dimension::with(['metrics', 'references', 'tools'])->get();

        
    }
}
