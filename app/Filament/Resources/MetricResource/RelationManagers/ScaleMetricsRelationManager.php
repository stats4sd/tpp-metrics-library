<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class ScaleMetricsRelationManager extends RelationManager
{
    protected static string $relationship = 'metrics';

    protected static ?string $recordTitleAttribute = 'title';

    public function getTableHeading(): string
    {
        return 'Metrics for ' . $this->ownerRecord->name;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Metric')
                    ->schema([
                        TextInput::make('title')
                            ->inlineLabel()
                            ->disabled(),
                    ]),
                Textarea::make('relation_notes')
                    ->label('Add any extra information about why this metric is associated to the scale'),
            ]);
    }

    public function table(Table $table): Table
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
                    ->preloadRecordSelect()
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
