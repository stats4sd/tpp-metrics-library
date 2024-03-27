<?php

namespace App\Filament\Resources\ToolResource\RelationManagers;

use App\Filament\SharedRelationManagers\SharedMetricsRelationManager;

class MetricsRelationManager extends SharedMetricsRelationManager
{
    protected static string $relationship = 'metrics';

    public function getMetricRelationManagerKey(): ?int
    {
        return 3;
    }


}
