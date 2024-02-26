<?php

namespace App\Console\Commands;

use App\Models\Dimension;
use App\Models\Metric;
use App\Models\Scale;
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

            // // ===== 3. Update or create metrics record ===== //
            $metric = $this->updateOrCreateMetricRecord($row);

            // ===== 4. Add record in link table metric_tool ===== //
            $this->handleToolId($metric, $row, 'Tool_ID');


            // ===== 5. Handle columns for entity tables and link tables ===== //
            $this->handleColumn($metric, $row, true, 'Scale of evaluation/reporting', Scale::class, 'reporting');
            $this->handleColumn($metric, $row, true, 'Dimensions (Economic; social; human; environmental; agricultural productivity; institutional)', Dimension::class, null);
            $this->handleColumn($metric, $row, true, 'Themes', Theme::class, null);
        }

        $this->info('done!');
    }


    // create record in link table metric_tool
    public function handleToolId($metric, $row, $columnName)
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

        $filename = 'storage/csv/metric_evaluations_18Oct23.csv';

        // Read CSV file content, call trim() to remove last blank line
        $csvFileContent = trim(File::get($filename));

        // Split by new line. Use the PHP_EOL constant for cross-platform compatibility.
        $lines = explode(PHP_EOL, $csvFileContent);

        // Extract the header and convert it into a Laravel collection.
        $header = collect(str_getcsv(array_shift($lines)));

        dump($header);

        // Convert the rows into a Laravel collection.
        $rows = collect($lines);

        // Map through the rows and combine them with the header to produce the final collection.
        $data = $rows->map(function ($row) use ($header) {

        dump(str_getcsv($row));
            $header->combine(str_getcsv($row));
        });

        dd($data);

        return $data;
    }


    public function updateOrCreateMetricRecord($row)
    {
        $title = trim($row['Metric/question']);

        $keyAttributes = [];
        $keyAttributes['title'] = $title;

        // if title is longer than 255 characters, add title content to note column for future reference
        if (Str::length($title) > 255) {
            $keyAttribute['notes'] = $title;
        }

        $otherAttributes = [];
        $otherAttributes['system_interest'] = $this->getSystemInterest($row['Does it measure the state of the system of interest or a pressure on the system of interest (i.e. external to system)? (options = state; pressure; NA; don\'t know)']);
        $otherAttributes['require_assessment'] = $this->getRequireAssessment($row['Does it require assessment of change or a comparison? (options = yes; no; don\'t know; NA)']);
        $otherAttributes['based'] = $this->getBased($row['Do the authors see the metric as being practice-based or performance based? (based on the perspective of the authors/assessment) (Options = practice; performance; don\'t know; NA)']);

        $metricModel = Metric::firstOrCreate(
            $keyAttributes,
            $otherAttributes,
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
