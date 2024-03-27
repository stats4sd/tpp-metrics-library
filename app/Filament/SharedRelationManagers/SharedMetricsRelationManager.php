<?php

namespace App\Filament\SharedRelationManagers;

use App\Filament\Resources\MetricResource;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class SharedMetricsRelationManager extends RelationManager
{
    protected static string $relationship = 'metrics';

    protected static ?string $recordTitleAttribute = 'title';

    // should be overwritten in the child classes. By default returns 0 (no relation manager)
    public function getMetricRelationManagerKey(): ?int
    {
        return null;
    }

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

                Checkbox::make('needs_review')
                    ->label('Mark this imported record as needs review')
                    ->columnSpan(2),

                Textarea::make('relation_notes')
                    ->label('Add any extra information about why this metric is associated to the dimension')
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
                Tables\Actions\ViewAction::make()->url(fn ($record, $livewire) => MetricResource::getUrl('view', ['record' => $record]) . '?activeRelationManager=' . $livewire->getMetricRelationManagerKey()),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
