<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ComplimentaryMetricsRelationManager extends RelationManager
{
    protected static string $relationship = 'complimentaryMetrics';
    protected static ?string $inverseRelationship = 'inverseComplimentaryMetrics';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = "1.g. Complimentary Metrics";

    public function getTableDescription(): string
    {
        return "Other metrics that are peers of this metric. Perhaps they are often used/collected together, or perhaps they are a measure of similar things or used for similar purposes.";
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
                Tables\Actions\AttachAction::make()
                    ->label('Attach Metric')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select->multiple())
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Placeholder::make('Notes')
                            ->content('Add any extra information about why this metric compliments the other'),
                        Textarea::make('notes'),
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
