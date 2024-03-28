<?php

namespace App\Console\Commands\OneTimeScripts;

use App\Models\Theme;
use App\Models\ThemeType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportThemeTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-theme-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import theme types specified in column themes.notes into tables theme_types and theme_theme_type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');

        // remove all existing records in tables theme_types, theme_theme_type
        Schema::disableForeignKeyConstraints();
        ThemeType::truncate();
        DB::table('theme_theme_type')->truncate();

        // get all themes records
        $themes = Theme::all();

        $this->comment('Handling ' . count($themes) . ' themes records...');

        // handle each theme record
        foreach ($themes as $theme) {
            $notes = str_replace("Types = ", "", $theme->notes);

            // change comma to semi-colon for as delimter for consistency
            $notes = str_replace(",", ";", $notes);

            // split the stiring into CSV values
            $entries = str_getcsv($notes, ';');

            foreach ($entries as $entry) {
                if (trim($entry) != '' && trim($entry) != null) {
                    // create or update theme_type record
                    $themeType = ThemeType::firstOrCreate(['name' => trim($entry)]);

                    // add record in mapping table theme_theme_type
                    $theme->themeTypes()->attach($themeType->id);
                }
            }
        }

        $this->info('done');
    }
}
