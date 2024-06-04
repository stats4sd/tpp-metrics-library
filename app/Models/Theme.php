<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Theme extends Model
{
    use HasFactory;

    protected $table = 'themes';

    protected $guarded = [];

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)->withTimestamps();
    }

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_theme')
            ->withPivot('relation_notes', 'needs_review')
            ->withTimestamps();
    }

    public function themeTypes(): BelongsToMany
    {
        return $this->belongsToMany(ThemeType::class)
            ->withPivot('unreviewed_import', 'relation_notes')
            ->withTimestamps();
    }
}
