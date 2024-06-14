<?php

namespace App\Filament\Resources\DimensionResource\RelationManagers;

use App\Filament\SharedRelationManagers\SharedMetricsRelationManager;

class MetricsRelationManager extends SharedMetricsRelationManager
{

    public function getMetricRelationManagerKey(): ?int
    {
        return 0;
    }
}
