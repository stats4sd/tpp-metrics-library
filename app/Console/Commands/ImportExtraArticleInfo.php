<?php

namespace App\Console\Commands;

use App\Models\Reference;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportExtraArticleInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-extra-article-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One-time import of extra article / reference metadata';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newData = $this->readCsvFileIntoCollection('storage/csv/articles.csv');

        $newData->each(function ($row) {
            $this->updateReferenceFromRow($row);
        });

        $extraData = $this->readCsvFileIntoCollection('storage/csv/rayyan_holistic_articles.csv');

        $extraData->each(function ($row) {
            $this->updateReferenceFromRow($row);
        });


    }


    public function readCsvFileIntoCollection($filename): Collection
    {
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
        $data = $rows->map(fn($row) => $header->combine(str_getcsv($row)));

        return $data;
    }

    /**
     * @param $row
     * @return void
     */
    function updateReferenceFromRow($row): void
    {
        $this->info('Updating Reference with key: ' . $row['key']);

        $key = Str::after($row['key'], 'rayyan-');

        $reference = Reference::updateOrCreate(
            ['rayyan_key' => $key],
            [
                'title' => $row['title'],
                'year' => (int)$row['year'],
                'journal' => $row['journal'],
                'volume' => (int)$row['volume'],
                'issue' => (int)$row['issue'],
                'pages' => (int)$row['pages'],
                'authors' => $row['authors'],
                'url' => $row['url'],
                'abstract' => $row['abstract'],
                'language' => $row['language'],
                'publisher' => $row['publisher'],
                'location' => $row['location'],
                'notes' => $row['notes'],
                'doi' => $row['doi'],
                'keywords' => $row['keywords'],
            ]
        );
    }

}
