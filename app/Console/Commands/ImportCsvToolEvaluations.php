<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\DataCollection;
use App\Models\DataType;
use App\Models\Developer;
use App\Models\Dimension;
use App\Models\Framework;
use App\Models\Framing;
use App\Models\IndicatorSelection;
use App\Models\MetricScale;
use App\Models\MetricUser;
use App\Models\Reference;
use App\Models\Scale;
use App\Models\Sdg;
use App\Models\Theme;
use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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


        // ===== 0. Remove previously imported records permanently (temporary for testing purpose only) ===== //
        $this->removePreviousImportedRecords();


        // ===== 1. Read CSV file into Laravel collection ===== //
        $data = $this->readCsvFileIntoCollection();


        // ===== 2. Handle CSV content line by line ===== //
        $this->comment('Handling ' . count($data) . ' records...');

        foreach ($data as $row) {

            // skip this row if include column is no
            if (Str::lower(trim($row['include'])) == 'no') {
                continue;
            }


            // ===== 3. Update or create tools record ===== //
            $tool = $this->updateOrCreateToolRecord($row);


            // ===== 4. Create placeholder for rayyan_ref if it is not existed ===== //
            $this->handleRayyanRef($row);


            // ===== 5. Handle columns for entity tables and link tables ===== //
            $this->handleColumn($tool, $row, true, 'developer', Developer::class, null);
            $this->handleColumn($tool, $row, true, 'sustain_framing', Framing::class, 'sustain');
            $this->handleColumn($tool, $row, false, 'dimensions', Dimension::class, null);

            $this->handleColumn($tool, $row, true, 'social_themes', Theme::class, 'social');
            $this->handleColumn($tool, $row, true, 'enviro_themes', Theme::class, 'enviro');
            $this->handleColumn($tool, $row, true, 'economic_themes', Theme::class, 'economic');
            $this->handleColumn($tool, $row, true, 'human_themes', Theme::class, 'human');
            $this->handleColumn($tool, $row, true, 'gov_themes', Theme::class, 'gov');
            $this->handleColumn($tool, $row, true, 'product_themes', Theme::class, 'product');

            $this->handleColumn($tool, $row, false, 'named_framework', Framework::class, null);

            // 'sustain_framing' seems to be a selection of a limited number of options ("Classical view", "Classical productive view", etc. After de-duplicating, I see 16 possible values.
            // However, the column 'Conceptual_framing' seems to be a free text field. Entries vary between short "named" framings ("Public Goods", "Sustainable Intensification" and references or longer descriptions. I don't think this is worth putting into a separate table at this stage, and it certainly seems quite different to the 'sustain_framing' column. For now, I'll put this as a property of the tool, and it might get extracted out to a separate table later.
            // $this->handleColumn($tool, $row, true, 'Conceptual_framing', Framing::class, 'conceptual');
            // $this->updateFramingDefinition($row, true, 'Conceptual_framing', Framing::class, $row['framing_definition']);

            $this->handleSdgs($tool, $row, 'sgd', Sdg::class);

            $this->handleColumn($tool, $row, true, 'stakeholder_design', MetricUser::class, null);

            $this->handleColumn($tool, $row, true, 'scale_measure', Scale::class, 'measurement');
            $this->handleColumn($tool, $row, true, 'scale_report', Scale::class, 'reporting');

            $this->handleColumn($tool, $row, true, 'country_use', Country::class, null);
            $this->handleColumn($tool, $row, true, 'target_user', MetricUser::class, null);
            $this->handleColumn($tool, $row, true, 'indicator_selection', IndicatorSelection::class, null);
            $this->handleColumn($tool, $row, true, 'data_type', DataType::class, null);
            $this->handleColumn($tool, $row, true, 'data_collection', DataCollection::class, null);
        }

        $this->info('done!');
    }


    // a generic function to create entity record and link table record for tool and entity
    public function handleColumn($tool, $row, $changeDelimiter, $columnName, $entityModel, $type)
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

                // if entry is longer than 255 characters, add entry content to note column for future reference
                if (Str::length($entry) > 255) {
                    $modelContent['notes'] = $entry;
                }

                // add entity record
                $model = $entityModel::firstOrCreate($modelContent);

                // optionally prepare array for other properties
                $array = [
                    'unreviewed_import' => 1,
                ];

                if ($type != null) {
                    $array['type'] = $type;
                }

                // add link table record
                $model->tools()->attach($tool->id, $array);
            }
        }
    }


    public function handleSdgs($tool, $row, $columnName, $entityModel)
    {
        $cellValue = $row[$columnName];

        // change delimiter
        // $this->info($cellValue);
        $cellValue = str_replace(',', ';', $cellValue);
        $cellValue = str_replace(':', ';', $cellValue);

        // suppose it should contains numbers only, remove ? and NA
        $cellValue = str_replace('?', '', $cellValue);
        $cellValue = str_replace('NA', '', $cellValue);

        // $this->info($cellValue);

        $entries = str_getcsv($cellValue, ';');
        foreach ($entries as $entry) {
            if (Str::lower(trim($entry)) != 'na' && Str::lower(trim($entry)) != '') {
                // $this->comment(Str::substr(trim($entry), 0, 254));

                // get entity record
                $model = $entityModel::find(Str::lower(trim($entry)));

                // add link table record
                $model->tools()->attach($tool->id);
            }
        }
    }


    public function updateFramingDefinition($row, $changeDelimiter, $columnName, $entityModel, $defintion)
    {
        $cellValue = $row[$columnName];

        // change delimiter if necessary
        if ($changeDelimiter) {
            $cellValue = str_replace(',', ';', $cellValue);
        }

        $entries = str_getcsv($cellValue, ';');
        foreach ($entries as $entry) {
            if (Str::lower(trim($entry)) != 'na' && Str::lower(trim($entry)) != '') {
                $model = $entityModel::where('name', Str::substr(trim($entry), 0, 254))->first();
                $model->definition = $defintion;
                $model->save();
            }
        }
    }


    public function removePreviousImportedRecords()
    {
        // for testing purpose only, delete previous imported records permanently
        $date = '2024-02-18';

        // DB level cascade delete will remove records in link table when entity records are deleted
        Tool::where('created_at', '>', $date)->forceDelete();
        Reference::where('created_at', '>', $date)->forceDelete();
        Developer::where('created_at', '>', $date)->forceDelete();
        Framing::where('created_at', '>', $date)->forceDelete();
        Dimension::where('created_at', '>', $date)->forceDelete();
        Theme::where('created_at', '>', $date)->forceDelete();
        Framework::where('created_at', '>', $date)->forceDelete();
        Sdg::where('created_at', '>', $date)->forceDelete();
        MetricUser::where('created_at', '>', $date)->forceDelete();
        Scale::where('created_at', '>', $date)->forceDelete();
        MetricScale::where('created_at', '>', $date)->forceDelete();
        Country::where('created_at', '>', $date)->forceDelete();
        IndicatorSelection::where('created_at', '>', $date)->forceDelete();
        DataType::where('created_at', '>', $date)->forceDelete();
        DataCollection::where('created_at', '>', $date)->forceDelete();
    }


    public function readCsvFileIntoCollection()
    {
        $filename = 'storage/csv/holistic_tools_clean_21Jan24.csv';
        // $filename = 'storage/csv/tool_evaluations_18Oct23_three_records.csv';

        // Read CSV file content, call trim() to remove last blank line
        $csvFileContent = trim(File::get($filename));

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
                //'reviewer_name' => $row['reviewer_name'], -- removed in 'cleaned' version
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

                'conceptual_framing' => $row['conceptual_framing'],
                'framing_definition' => $row['framing_definition'],

                'framing_indicator_link' => $this->getBoolean($row['framing_indicator_link']),
                'indicator_convenience' => $row['indicator_convenience'],
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
                //'metric_no' => $row['metric_no'],

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

                //'metric_eval' => $this->getBoolean($row['metric_eval']),
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

                $referenceModel->tools()->attach($row['tool_id']);

            }
        }
    }


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
