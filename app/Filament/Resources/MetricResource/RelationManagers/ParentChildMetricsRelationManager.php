<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ParentChildMetricsRelationManager extends RelationManager
{
    protected static string $relationship = 'relatedMetrics';
    protected static ?string $inverseRelationship = 'parent';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = "1.f. Derived / Related Metrics";

    public function getTableDescription(): string
    {
        return "Other metrics that are derived from or closely related to this metric. For example, they could be metrics that measure a subset of what this metric is measuring, or a more context-specific version of this metric.";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make()
                    ->label('Attach Metric')
                    ->preloadRecordSelect()
                    ->form(fn(Tables\Actions\AssociateAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('notes')->hint('Add any extra information about why this metric compliments the other'),
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
