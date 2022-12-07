<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function metrics()
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    // class relationships

    public function dimensions()
    {
        return $this->belongsToMany(Dimension::class)
            ->withPivot('notes');
    }

    public function metricProperties()
    {
        return $this->belongsToMany(MetricProperty::class)
            ->withPivot('notes');
    }
}
