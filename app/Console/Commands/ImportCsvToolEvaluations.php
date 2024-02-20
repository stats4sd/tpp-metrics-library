<?php

namespace App\Console\Commands;

use App\Models\Tool;
use App\Models\Scale;
use App\Models\Developer;
use App\Models\Dimension;
use App\Models\Framework;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ImportCsvToolEvaluations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-csv-tool-evaluations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CSV file for tool evaluations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = 'storage/csv/tool_evaluations_18Oct23.csv';

        $this->info('start');


        // for testing purpose only, delete previous imported records permanently
        Tool::where('created_at', '>', '2024-02-18')->forceDelete();
        Developer::where('created_at', '>', '2024-02-18')->forceDelete();
        Dimension::where('created_at', '>', '2024-02-18')->forceDelete();
        Framework::where('created_at', '>', '2024-02-18')->forceDelete();
        Scale::where('created_at', '>', '2024-02-18')->forceDelete();


        // ===== Read CSV file into Laravel collection ===== //

        // 0. Read CSV file content
        $csvFileContent = File::get($filename);

        // 1. Split by new line. Use the PHP_EOL constant for cross-platform compatibility.
        $lines = explode(PHP_EOL, $csvFileContent);

        // 2. Extract the header and convert it into a Laravel collection.
        $header = collect(str_getcsv(array_shift($lines)));

        // 3. Convert the rows into a Laravel collection.
        $rows = collect($lines);

        // 4. Map through the rows and combine them with the header to produce the final collection.
        $data = $rows->map(fn ($row) => $header->combine(str_getcsv($row)));


        // ===== Handle CSV content line by line ===== //

        foreach ($data as $row) {
            $tool = Tool::updateOrCreate(['name' => $row['tool_name']]);
        }


        $this->info('done!');
    }


    public function getValue($value)
    {
        $array = ['na', 'n/a', 'null', ''];

        if (in_array(Str::lower(trim($value)), $array)) {
            return null;
        } else {
            return trim($value);
        }
    }
}
