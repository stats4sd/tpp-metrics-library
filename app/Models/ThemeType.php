<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ThemeType extends Model
{
    use HasFactory;

    protected $table = 'theme_types';

    protected $guarded = [];

    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class)
            ->withPivot('unreviewed_import', 'relation_notes')
            ->withTimestamps();
    }
}
