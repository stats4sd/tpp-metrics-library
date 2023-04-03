<?php

namespace App\Imports;

use App\Models\Scale;
use App\Models\Method;
use App\Models\Metric;
use App\Models\Dimension;
use App\Models\Geography;
use App\Models\Reference;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScreeningImporter implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

        foreach($collection as $row) {

            $reference = Reference::firstWhere('rayyan_key', $row['key']);

            if($row['decision'] === 'exclude') {

                $reference->delete();

            }

            elseif($row['decision'] === 'include') {

                $str = Str::of($row['corrected'])
                    ->split('/[\n\r]/');

                foreach($str as $line) {

                    $line = Str::of($line)
                        ->trim();

                    $type = $line->before(":");
                    $items = $line->after(':');

                    $items = collect(str_getcsv($items));

                    $items = $items->map(fn($item) => Str::of($item)
                        ->trim()
                        ->toString())
                        ->filter(fn($item) => $item !== '');

                    foreach ($items as $item) {

                        if($type->toString() === "Metrics") {

                            $metric = Metric::updateOrCreate(['title' => $item]);
                            $reference->metrics()->syncWithoutDetaching($metric->id, ['reference_type' => 'reference']);

                        }

                        elseif($type->toString()=== "Dimensions") {

                            $dimension = Dimension::updateOrCreate(['name' => $item]);
                            $reference->dimensions()->syncWithoutDetaching($dimension->id, ['reference_type' => 'reference']);

                        }
    
                        elseif($type->toString()=== "Methods") {
    
                            $method = Method::updateOrCreate(['name' => $item]);
                            $reference->methods()->syncWithoutDetaching($method->id, ['reference_type' => 'reference']);

                        }
    
                        elseif($type->toString()=== "Country/region") {
    
                            $geography = Geography::updateOrCreate(['name' => $item]);
                            $reference->geographies()->syncWithoutDetaching($geography->id, ['reference_type' => 'reference']);

                        }
    
                        elseif($type->toString()=== "Scale" || $type->toString()===  "Level") {
    
                            $scale = Scale::updateOrCreate(['name' => $item]);
                            $reference->scales()->syncWithoutDetaching($scale->id, ['reference_type' => 'reference']);

                        }

                    }   

                }

            }

        }

    }

}
