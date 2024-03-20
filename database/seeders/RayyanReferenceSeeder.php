<?php

namespace Database\Seeders;

use App\Models\Reference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RayyanReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \JsonException
     */
    public function run()
    {
        Reference::truncate();

        // get data from csv file
        $references = json_decode(
            json: file_get_contents(base_path('database/data/articles.json')),
            associative: true,
            flags: JSON_THROW_ON_ERROR);

        $count = 0;
        foreach($references as $reference) {
            Reference::create([
                'rayyan_key' => Str::replace('rayyan-', '', $reference['key']),
                'title' => $reference['title'],
                'url' => is_string($reference['url']) ? $reference['url'] : '',
                'doi' => $reference['doi'],
                'notes' => $reference['notes'],
                ]);
        }


    }
}
