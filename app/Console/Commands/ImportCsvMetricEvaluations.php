<?php

namespace App\Console\Commands;

use App\Models\Dimension;
use App\Models\Metric;
use App\Models\Theme;
use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportCsvMetricEvaluations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-csv-metric-evaluations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CSV file for metric evaluations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');


        // ===== 0. Remove previously imported records permanently (temporary for testing purpose only) ===== //
        $this->removePreviousImportedRecords();


        // ===== 1. Read CSV file into Laravel collection ===== //
        $data = $this->readCsvFileIntoCollection();


        // ===== 2. Handle CSV content line by line ===== //
        $this->comment('Handling ' . count($data) . ' records...');


        foreach ($data as $row) {

            $this->info('Handling record ' . $row['metric_question'] . '...');

            // // ===== 3. Update or create metrics record ===== //
            $metric = $this->updateOrCreateMetricRecord($row);

            // ===== 4. Add record in link table metric_tool ===== //
            $this->handleToolId($metric, $row, 'tool_id');


            // ===== 5. Handle columns for entity tables and link tables ===== //
            $this->handleColumn($metric, $row, true, 'dimensions', Dimension::class, null);
            $this->handleColumn($metric, $row, true, 'themes', Theme::class, null);
        }

        $this->info('done!');
    }


    // create record in link table metric_tool
    public function handleToolId($metric, $row, $columnName): void
    {
        $toolId = $row[$columnName];

        // if there is a tool id
        if (trim($toolId) != '') {
            // find tool model
            $tool = Tool::find(trim($toolId));

            // add link table record
            $metric->tools()->attach($tool->id);
        }
    }


    // a generic function to create entity record and link table record for tool and entity
    public function handleColumn($metric, $row, $changeDelimiter, $columnName, $entityModel, $type)
    {
        $cellValue = $row[$columnName];

        // change delimiter if necessary
        if ($changeDelimiter) {
            // $this->info($cellValue);
            $cellValue = str_replace(',', ';', $cellValue);
        }

        // $this->info($cellValue);

        $entries = str_getcsv($cellValue, ';');
        foreach ($entries as $entry) {
            if (Str::lower(trim($entry)) != 'na' && Str::lower(trim($entry)) != '') {
                // $this->comment(Str::substr(trim($entry), 0, 254));

                $modelContent = [];
                $modelContent['name'] = Str::substr(trim($entry), 0, 254);

                // add entry is longer than 255 characters, add entry content to note column for future reference
                if (Str::length($entry) > 255) {
                    $modelContent['notes'] = $entry;
                }

                // add entity record
                $model = $entityModel::firstOrCreate($modelContent);

                // optionally prepare array for other properties
                $array = [];
                if ($type != null) {
                    $array['type'] = $type;
                }

                // add link table record
                $model->metrics()->attach($metric->id, $array);
            }
        }
    }


    public function removePreviousImportedRecords()
    {
        // for testing purpose only, delete previous imported records permanently
        $date = '2024-02-18';

        // DB level cascade delete will remove records in link table when entity records are deleted
        Metric::where('created_at', '>', $date)->forceDelete();

        // below enetiy records should be deleted when running command to import tool evaluation csv file
        // Scale::where('created_at', '>', $date)->forceDelete();
        // Theme::where('created_at', '>', $date)->forceDelete();
        // Dimension::where('created_at', '>', $date)->forceDelete();
    }


    public function readCsvFileIntoCollection()
    {
        // TODO: the original csv file cannot be read properly, possible cause should be related to tidiness of some rows

        $filename = 'storage/csv/holistic_metrics_clean_21Jan24.csv';

        // Read CSV file content, call trim() to remove last blank line
        $csvFileContent = trim(File::get($filename));

        // remove newlines within cells
        $csvFileContent = preg_replace('/\"(.+)\n\"/', '$1', $csvFileContent);


        // Split by new line. Use the PHP_EOL constant for cross-platform compatibility.
        $lines = explode(PHP_EOL, $csvFileContent);

        // Extract the header and convert it into a Laravel collection.
        $header = collect(str_getcsv(array_shift($lines)));

        // Convert the rows into a Laravel collection.
        $rows = collect($lines);

        // Map through the rows and combine them with the header to produce the final collection.
        $data = $rows->map(function ($row) use ($header) {
            return $header->combine(str_getcsv($row));
        });

        return $data;
    }


    public function updateOrCreateMetricRecord($row): Metric
    {
        $title = trim($row['metric_question']);

        $keyAttributes = [];
        $keyAttributes['title'] = Str::limit($title, 254, '');

        // if title is longer than 255 characters, add title content to note column for future reference
        if (Str::length($title) > 255) {
            $keyAttribute['notes'] = $title;
        }

        $metricModel = Metric::firstOrCreate(
            $keyAttributes,
        );

        return $metricModel;
    }



    public function getSystemInterest($value)
    {
        // define what are considered as null
        $array = ['', 'don\'t know'];

        if (in_array(Str::lower(trim($value)), $array)) {
            return null;
        } else {
            return trim($value);
        }
    }


    public function getRequireAssessment($value)
    {
        // define what are considered as true or false, anything else are considered as null
        $trueArray = ['yes'];
        $falseArray = ['no'];

        if (in_array(Str::lower(trim($value)), $trueArray)) {
            return true;
        } else if (in_array(Str::lower(trim($value)), $falseArray)) {
            return false;
        } else {
            return null;
        }
    }


    public function getBased($value)
    {
        // define what are considered as null
        $array = ['', 'don\'t know'];

        if (in_array(Str::lower(trim($value)), $array)) {
            return null;
        } else {
            return trim($value);
        }
    }
}
