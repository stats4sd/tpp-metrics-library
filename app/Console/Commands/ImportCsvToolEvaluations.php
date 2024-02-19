<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Tool;
use App\Models\Scale;
use App\Models\Currency;
use App\Models\Developer;
use App\Models\Dimension;
use App\Models\Framework;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\GetOneDayExchangeRates;
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
        $this->info('start');

        $filename = 'storage/csv/tool_evaluations_18Oct23.csv';
        $delimiter = ',';
        $lineNumber = 0;

        // delete previous imported records permanently
        Tool::where('created_at', '>', '2024-02-18')->forceDelete();
        Developer::where('created_at', '>', '2024-02-18')->forceDelete();
        Dimension::where('created_at', '>', '2024-02-18')->forceDelete();
        Framework::where('created_at', '>', '2024-02-18')->forceDelete();
        Scale::where('created_at', '>', '2024-02-18')->forceDelete();

        // return;

        // ========== //

        // open file
        if (($handle = fopen($filename, 'r')) !== false) {

            // handle each row
            while (($row = fgetcsv($handle, 10000, $delimiter)) !== false) {
                $lineNumber = $lineNumber + 1;

                // skip first row for header
                if ($lineNumber == 1) {
                    continue;
                }

                $this->comment('*** Line ' . $lineNumber);

                // tool_name
                $value = $this->getValue($row[5]);
                $this->comment($value);

                if ($value != null) {
                    // update or create tools record
                    $tool = Tool::updateOrCreate(['name' => $value]);
                }


                // rayyan_ref
                $this->comment($row[7]);

                // Question: do we need to find related metrics via rayyan_ref, then add relationship between metrics and tools?
                // But we have not imported CSV file for metrics evaluations yet...
                $rayyanRefs = str_getcsv($row[7], ';');
                foreach ($rayyanRefs as $rayyanRef) {
                    $this->comment(trim($rayyanRef));
                }


                // developer
                $value = $this->getValue($row[10]);
                $this->comment($value);

                if ($value != null) {
                    // update or create tools record
                    $developer = Developer::updateOrCreate(['name' => $value]);
                }


                // dimensions
                $value = $this->getValue($row[23]);
                $this->comment($value);

                if ($value != null) {
                    // update or create tools record
                    $dimension = Dimension::updateOrCreate(['name' => $value]);
                }


                // named_framework
                $value = $this->getValue($row[32]);
                $this->comment($value);

                if ($value != null) {
                    // update or create tools record
                    $framework = Framework::updateOrCreate(['name' => $value]);
                }


                // scale_measure
                $value = $this->getValue($row[41]);
                $this->comment($value);

                if ($value != null) {
                    // update or create tools record
                    $scaleMeasures = str_getcsv($value, ';');
                    foreach ($scaleMeasures as $scaleMeasure) {
                        $this->comment(trim($scaleMeasure));

                        $scale = Scale::updateOrCreate(['name' => trim($scaleMeasure)]);
                    }
                }
            }

            // close file
            fclose($handle);
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
