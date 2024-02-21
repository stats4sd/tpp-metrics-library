<?php

namespace App\Console\Commands;

use App\Models\Tool;
use App\Models\Scale;
use App\Models\Developer;
use App\Models\Dimension;
use App\Models\Framework;
use App\Models\Reference;
use App\Models\MetricUser;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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
        $this->info('start');


        // ===== 0. Remove previous imported records (temporary for testing only) ===== //
        $this->removePreviousImportedRecords();


        // ===== 1. Read CSV file into Laravel collection ===== //
        $data = $this->readCsvFileIntoCollection();


        // ===== 2. Handle CSV content line by line ===== //

        foreach ($data as $row) {

            // skip this row if include column is no
            if (Str::lower(trim($row['include'])) == 'no') {
                continue;
            }


            // ===== 3. Update or create tools record ===== //
            $toolModel = $this->updateOrCreateToolRecord($row);


            // ===== 4. Create placeholder for rayyan_ref if it is not existed ===== //
            $this->handleRayyanRef($row);


            // ===== 5. Handle columns for relationship with existing tables ===== //
            // $this->handleDeveloper($row);
            // $this->handleDimension($row);
            // $this->handleFramework($row);
            // $this->handleStakeholderDesign($row);
            // $this->handleScaleMeasure($row);
            // $this->handleScaleReport($row);

            $this->handleColumn($row, ',', ';', 'developer', Developer::class);
            $this->handleColumn($row, null, ';', 'dimensions', Dimension::class);
            $this->handleColumn($row, null, ';', 'named_framework', Framework::class);
            $this->handleColumn($row, ',', ';', 'stakeholder_design', MetricUser::class);
            $this->handleColumn($row, ',', ';', 'scale_measure', Scale::class);
            $this->handleColumn($row, ',', ';', 'scale_report', Scale::class);
        }

        $this->info('done!');
    }


    // a generic function to create entity record and relationship between tool and entity
    public function handleColumn($row, $fromDelimiter, $toDelimiter, $columnName, $entityModel)
    {
        $cellValue = $row[$columnName];

        // change delimiter if necessary
        if ($fromDelimiter != null && $toDelimiter != null) {
            // $this->info($cellValue);
            $cellValue = str_replace($fromDelimiter, $toDelimiter, $cellValue);
        }

        // $this->info($cellValue);

        $entries = str_getcsv($cellValue, $toDelimiter);
        foreach ($entries as $entry) {
            if (Str::lower(trim($entry)) != 'na' && Str::lower(trim($entry)) != '') {
                // $this->comment(Str::substr(trim($entry), 0, 254));
                $model = $entityModel::firstOrCreate([
                    'name' => Str::substr(trim($entry), 0, 254),
                ]);
            }
        }

        // TODO: add records in link table
    }




    public function removePreviousImportedRecords()
    {
        // for testing purpose only, delete previous imported records permanently
        $date = '2024-02-18';

        Tool::where('created_at', '>', $date)->forceDelete();
        Reference::where('created_at', '>', $date)->forceDelete();
        Developer::where('created_at', '>', $date)->forceDelete();
        Dimension::where('created_at', '>', $date)->forceDelete();
        Framework::where('created_at', '>', $date)->forceDelete();
        MetricUser::where('created_at', '>', $date)->forceDelete();
        Scale::where('created_at', '>', $date)->forceDelete();
    }


    public function readCsvFileIntoCollection()
    {
        $filename = 'storage/csv/tool_evaluations_18Oct23.csv';
        // $filename = 'storage/csv/tool_evaluations_18Oct23_three_records.csv';

        // Read CSV file content
        $csvFileContent = File::get($filename);

        // Split by new line. Use the PHP_EOL constant for cross-platform compatibility.
        $lines = explode(PHP_EOL, $csvFileContent);

        // Extract the header and convert it into a Laravel collection.
        $header = collect(str_getcsv(array_shift($lines)));

        // Convert the rows into a Laravel collection.
        $rows = collect($lines);

        // Map through the rows and combine them with the header to produce the final collection.
        $data = $rows->map(fn ($row) => $header->combine(str_getcsv($row)));

        return $data;
    }


    public function updateOrCreateToolRecord($row)
    {
        $toolModel = Tool::updateOrCreate(
            ['id' => $row['tool_id'], 'name' => $row['tool_name']],

            [
                'reviewer_name' => $row['reviewer_name'],
                'acronym' => $row['acronym'],
                'web_ref' => $row['web_ref'],
                'author' => $row['author'],
                'year_published' => $this->getYear($row['year_published']),

                'updated' => $this->getBoolean($row['updated']),
                'year_updated' => $this->getYear($row['year_updated']),
                'updated_ref' => $row['updated_ref'],
                'promoted_tool' => $this->getBoolean($row['promoted_tool']),
                'specify_indicators' => $this->getBoolean($row['specify_indicators']),

                'wider_use' => $this->getBoolean($row['wider_use']),
                'wider_use_evidence' => $row['wider_use_evidence'],
                'wider_use_notes' => $row['wider_use_notes'],
                'adapted' => $this->getBoolean($row['adapted']),
                'adapted_ref' => $row['adapted_ref'],

                'framing_definition' => $row['framing_definition'],
                'framing_indicator_link' => $this->getBoolean($row['framing_indicator_link']),
                'indicator_convenience' => $row['Indicator_convenience'],
                'sustainability_view' => $row['sustainability_view'],
                'tool_orientiation' => $row['tool_orientiation'],

                'localisable' => $row['localisable'],
                'system_type' => $row['system_type'],
                'visualise_framework' => $this->getBoolean($row['visualise_framework']),
                'intended_function' => $row['intended_function'],
                'comparison_type' => $row['comparison_type'],

                'verifiable' => $this->getBoolean($row['verifiable']),
                'local_indicators' => $this->getBoolean($row['local_indicators']),
                'stakeholder_involved' => $this->getBoolean($row['stakeholder_involved']),
                'complexity' => $row['complexity'],
                'access' => $row['access'],

                'paid_access' => $this->getBoolean($row['paid_access']),
                'online_platform' => $this->getBoolean($row['online_platform']),
                'guide_assess' => $this->getBoolean($row['guide_assess']),
                'guide_analysis' => $this->getBoolean($row['guide_analysis']),
                'guide_interpret' => $this->getBoolean($row['guide_interpret']),

                'guide_data_gov' => $this->getBoolean($row['guide_data_gov']),
                'informed_consent' => $this->getBoolean($row['informed_consent']),
                'visualise_result' => $this->getBoolean($row['visualise_result']),
                'visualise_type' => $row['visualise_type'],
                'assessment_results' => $row['assessment_results'],

                'metric_no' => $row['metric_no'],
                'collection_time' => $row['collection_time'],
                'interval' => $row['interval'],
                'interaction' => $this->getBoolean($row['interaction']),
                'interaction_expl' => $row['interaction_expl'],

                'scaleable' => $this->getBoolean($row['scaleable']),
                'aggregation' => $this->getBoolean($row['aggregation']),
                'weighting' => $this->getBoolean($row['weighting']),
                'weighting_preference' => $row['weighting_preference'],
                'comments' => $row['comments'],

                'once_multi' => $row['once_multi'],
                'metric_eval' => $this->getBoolean($row['metric_eval']),
            ]
        );

        return $toolModel;
    }


    public function handleRayyanRef($row)
    {
        // unify delimiter to semicolon, change all commas to semicolons first
        $colRayyanRef = str_replace(',', ';', $row['rayyan_ref']);

        $rayyanRefs = str_getcsv($colRayyanRef, ';');
        foreach ($rayyanRefs as $rayyanRef) {
            if (Str::lower(trim($rayyanRef)) != 'na' && Str::lower(trim($rayyanRef)) != '') {
                $referenceModel = Reference::firstOrCreate([
                    'name' => trim($rayyanRef),
                    'rayyan_key' => trim($rayyanRef),
                ]);
            }
        }
    }


    // public function handleDeveloper($row)
    // {
    //     // unify delimiter to semicolon, change all commas to semicolons first
    //     $colDeveloper = str_replace(',', ';', $row['developer']);

    //     $developers = str_getcsv($colDeveloper, ';');
    //     foreach ($developers as $developer) {
    //         if (Str::lower(trim($developer)) != 'na' && Str::lower(trim($developer)) != '') {
    //             $developerModel = Developer::firstOrCreate([
    //                 'name' => trim($developer),
    //             ]);
    //         }
    //     }

    //     // Question: need to add a link table to define relationship with tools?
    // }


    // public function handleDimension($row)
    // {
    //     $dimensions = str_getcsv($row['dimensions'], ';');
    //     foreach ($dimensions as $dimension) {
    //         if (Str::lower(trim($dimension)) != 'na' && Str::lower(trim($dimension)) != '') {
    //             $dimensionModel = Dimension::firstOrCreate([
    //                 'name' => trim($dimension),
    //             ]);
    //         }
    //     }

    //     // Question: need to add a link table to define relationship with tools?
    // }


    // public function handleFramework($row)
    // {
    //     $frameworks = str_getcsv($row['named_framework'], ';');
    //     foreach ($frameworks as $framework) {
    //         if (Str::lower(trim($framework)) != 'na' && Str::lower(trim($framework)) != '') {
    //             $frameworkModel = Framework::firstOrCreate([
    //                 'name' => Str::substr(trim($framework), 0, 254),
    //             ]);
    //         }
    //     }

    //     // TODO: add records in link table framework_tool
    // }


    // public function handleStakeholderDesign($row)
    // {
    //     // unify delimiter to semicolon, change all commas to semicolons first
    //     $colStakeholderDesign = str_replace(',', ';', $row['stakeholder_design']);

    //     $stakeholderDesigns = str_getcsv($colStakeholderDesign, ';');
    //     foreach ($stakeholderDesigns as $stakeholderDesign) {
    //         if (Str::lower(trim($stakeholderDesign)) != 'na' && Str::lower(trim($stakeholderDesign)) != '') {
    //             $metricUserModel = MetricUser::firstOrCreate([
    //                 'name' => trim($stakeholderDesign),
    //             ]);
    //         }
    //     }

    //     // TODO: add records in link table metric_metric_user
    // }


    // public function handleScaleMeasure($row)
    // {
    //     // unify delimiter to semicolon, change all commas to semicolons first
    //     $colScaleMeasure = str_replace(',', ';', $row['scale_measure']);

    //     $scaleMeasures = str_getcsv($colScaleMeasure, ';');
    //     foreach ($scaleMeasures as $scaleMeasure) {
    //         if (Str::lower(trim($scaleMeasure)) != 'na' && Str::lower(trim($scaleMeasure)) != '') {
    //             $scaleModel = Scale::firstOrCreate([
    //                 'name' => trim($scaleMeasure),
    //             ]);
    //         }
    //     }

    //     // TODO: add records in link table metric_scale
    // }

    // public function handleScaleReport($row)
    // {
    //     // unify delimiter to semicolon, change all commas to semicolons first
    //     $colScaleReport = str_replace(',', ';', $row['scale_report']);

    //     $scaleReports = str_getcsv($colScaleReport, ';');
    //     foreach ($scaleReports as $scaleReport) {
    //         if (Str::lower(trim($scaleReport)) != 'na' && Str::lower(trim($scaleReport)) != '') {
    //             $scaleModel = Scale::firstOrCreate([
    //                 'name' => trim($scaleReport),
    //             ]);
    //         }
    //     }

    //     // TODO: add records in link table metric_scale
    // }



    public function getYear($value)
    {
        // define what are considered as null
        $array = ['na', 'n/a', 'no', 'unknown'];

        if (in_array(Str::lower(trim($value)), $array)) {
            return null;
        } else {
            return trim($value);
        }
    }


    public function getBoolean($value)
    {
        // define what are considered as true or false, anything else are considered as null
        $trueArray = ['yes', 'yes (framework)', 'yes (article is not open access', 'yea', 'complete'];
        $falseArray = ['no', 'yno', 'no; expert consultation'];

        if (in_array(Str::lower(trim($value)), $trueArray)) {
            return true;
        } else if (in_array(Str::lower(trim($value)), $falseArray)) {
            return false;
        } else {
            return null;
        }
    }
}
