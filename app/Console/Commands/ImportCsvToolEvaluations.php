<?php

namespace App\Console\Commands;

use App\Models\Tool;
use App\Models\Scale;
use App\Models\Developer;
use App\Models\Dimension;
use App\Models\Framework;
use App\Models\Reference;
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
        $filename = 'storage/csv/tool_evaluations_18Oct23.csv';
        // $filename = 'storage/csv/tool_evaluations_18Oct23_three_records.csv';

        $this->info('start');


        // for testing purpose only, delete previous imported records permanently
        $date = '2024-02-18';

        Tool::where('created_at', '>', $date)->forceDelete();
        Reference::where('created_at', '>', $date)->forceDelete();

        Developer::where('created_at', '>', $date)->forceDelete();
        Dimension::where('created_at', '>', $date)->forceDelete();
        Framework::where('created_at', '>', $date)->forceDelete();
        Scale::where('created_at', '>', $date)->forceDelete();


        // ===== 1. Read CSV file into Laravel collection ===== //

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


        // ===== 2. Handle CSV content line by line ===== //

        foreach ($data as $row) {

            // skip this row if include column is no
            if (Str::lower(trim($row['include'])) == 'no') {
                continue;
            }


            // ===== 3. Update or create tools record ===== //
            $tool = Tool::updateOrCreate(
                ['id' => $row['tool_id'], 'name' => $row['tool_name']],

                [
                    'reviewer_name' => $row['reviewer_name'],
                    'acronym' => $row['acronym'],
                    'web_ref' => $row['web_ref'],
                    'author' => $row['author'],
                    'year_published' => $this->handleYear($row['year_published']),

                    'updated' => $this->handleBoolean($row['updated']),
                    'year_updated' => $this->handleYear($row['year_updated']),
                    'updated_ref' => $row['updated_ref'],
                    'promoted_tool' => $this->handleBoolean($row['promoted_tool']),
                    'specify_indicators' => $this->handleBoolean($row['specify_indicators']),

                    'wider_use' => $this->handleBoolean($row['wider_use']),
                    'wider_use_evidence' => $row['wider_use_evidence'],
                    'wider_use_notes' => $row['wider_use_notes'],
                    'adapted' => $this->handleBoolean($row['adapted']),
                    'adapted_ref' => $row['adapted_ref'],

                    'framing_definition' => $row['framing_definition'],
                    'framing_indicator_link' => $this->handleBoolean($row['framing_indicator_link']),
                    'indicator_convenience' => $row['Indicator_convenience'],
                    'sustainability_view' => $row['sustainability_view'],
                    'tool_orientiation' => $row['tool_orientiation'],

                    'localisable' => $row['localisable'],
                    'system_type' => $row['system_type'],
                    'visualise_framework' => $this->handleBoolean($row['visualise_framework']),
                    'intended_function' => $row['intended_function'],
                    'comparison_type' => $row['comparison_type'],

                    'verifiable' => $this->handleBoolean($row['verifiable']),
                    'local_indicators' => $this->handleBoolean($row['local_indicators']),
                    'stakeholder_involved' => $this->handleBoolean($row['stakeholder_involved']),
                    'complexity' => $row['complexity'],
                    'access' => $row['access'],

                    'paid_access' => $this->handleBoolean($row['paid_access']),
                    'online_platform' => $this->handleBoolean($row['online_platform']),
                    'guide_assess' => $this->handleBoolean($row['guide_assess']),
                    'guide_analysis' => $this->handleBoolean($row['guide_analysis']),
                    'guide_interpret' => $this->handleBoolean($row['guide_interpret']),

                    'guide_data_gov' => $this->handleBoolean($row['guide_data_gov']),
                    'informed_consent' => $this->handleBoolean($row['informed_consent']),
                    'visualise_result' => $this->handleBoolean($row['visualise_result']),
                    'visualise_type' => $row['visualise_type'],
                    'assessment_results' => $row['assessment_results'],

                    'metric_no' => $row['metric_no'],
                    'collection_time' => $row['collection_time'],
                    'interval' => $row['interval'],
                    'interaction' => $this->handleBoolean($row['interaction']),
                    'interaction_expl' => $row['interaction_expl'],

                    'scaleable' => $this->handleBoolean($row['scaleable']),
                    'aggregation' => $this->handleBoolean($row['aggregation']),
                    'weighting' => $this->handleBoolean($row['weighting']),
                    'weighting_preference' => $row['weighting_preference'],
                    'comments' => $row['comments'],

                    'once_multi' => $row['once_multi'],
                    'metric_eval' => $this->handleBoolean($row['metric_eval']),
                ]
            );


            // ===== 4. Create placeholder for rayyan_ref if it is not existed ===== //

            // unify delimiter to semicolon, change all commas to semicolons first
            $rayyanRef = str_replace(',', ';', $row['rayyan_ref']);

            $rayyanRefs = str_getcsv($rayyanRef, ';');
            foreach ($rayyanRefs as $rayyanRef) {
                $rayyanKey = Str::lower(trim($rayyanRef));

                if (Str::lower(trim($rayyanRef)) != 'na') {
                    $reference = Reference::firstOrCreate([
                        'name' => $rayyanKey,
                        'rayyan_key' => $rayyanKey,
                    ]);
                }
            }
        }

        $this->info('done!');
    }


    public function handleYear($value)
    {
        // define what are considered as null
        $array = ['na', 'n/a', 'no', 'unknown'];

        if (in_array(Str::lower(trim($value)), $array)) {
            return null;
        } else {
            return trim($value);
        }
    }


    public function handleBoolean($value)
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
