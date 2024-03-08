<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;

class MetricUserMetricsRelationManager extends RelationManager
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

                Toggle::make('needs_review')
                    ->label('Mark this imported record as needs review')
                    ->columnSpan(2)
                    ->offColor('success')
                    ->onColor('danger')
                    ->offIcon('heroicon-s-check')
                    ->onIcon('heroicon-s-exclamation-circle'),

                Textarea::make('relation_notes')
                    ->label('Add any extra information about why this metric is associated to the metric user')
                    ->columnSpan(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                IconColumn::make('needs_review')
                    ->options(['heroicon-o-exclamation-circle' => fn ($state): bool => (bool)$state])
                    ->color('danger')
                    ->sortable(),
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
