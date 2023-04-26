<?php

namespace App\Imports;


use App\Models\Reference;
use App\Jobs\ProcessImporter;
use Illuminate\Support\Collection;
use App\Jobs\ProcessImportNotifcation;
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

            if($row['decision'] === 'exclude' && isset($reference)) {

                $reference->delete();

            }

            elseif($row['decision'] === 'include' && is_null($reference->imported_entities)) {

                $reference->update(['imported_entities' => $row['corrected']]);

                ProcessImporter::dispatch($reference);

            }

        }

        $recipient = auth()->user();
        ProcessImportNotifcation::dispatch($recipient);

    }

}
