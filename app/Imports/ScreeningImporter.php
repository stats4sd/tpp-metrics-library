<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScreeningImporter implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        dump('hi from importer');

        $metrics = collect([]);

        foreach($collection as $row) {


            if($row['decision'] !== 'include') {
                continue;
            }

            $str = Str::of($row['corrected'])
                ->split('/[\n\r]/');

            foreach($str as $line) {
                $line = Str::of($line)
                    ->trim();

                $type = $line->before(":");
                $items = $line->after(':');

                dump('type: ' . $type);
               // dump('items: ' . $items);

                if($type->toString() === "Metrics") {
                    $items = collect(str_getcsv($items));
                    dump($items);

                    $items = $items->map(fn($item) => Str::of($item)
                        ->trim()
                        //->lower()  (3961 when all lowercase; 4188 when not; 
                        ->toString())
                        ->filter(fn($item) => $item !== '');

                    dump($items);
                    $metrics = $metrics->merge($items);
                }
            }
        }

        dump($metrics->unique());
        dump('########################');
        dump('Mertic Count: ' . $metrics->unique()->count());
        dump('########################');

    }
}
