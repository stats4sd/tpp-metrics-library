<?php

namespace App\Models;

use App\Imports\ScreeningImporter;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Import extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::created(function (Import $import) {
            Excel::import(new ScreeningImporter, $import->file, 'screening-imports');
            // dd('done');
        });

    }

}
