<?php

namespace App\Console\Commands;

use App\Imports\ScreeningImporter;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       Excel::import(new ScreeningImporter, base_path('gpt_checked_output_27_mar_23.csv'));

        return Command::SUCCESS;
    }
}
