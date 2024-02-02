<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Forms\Components\Textarea;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use App\Models\Metric;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class ChildMetricsRelationManager extends RelationManager
{
    protected static string $relationship = 'childMetrics';
    protected static ?string $inverseRelationship = 'parentMetrics';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = "1.f. Derived / Related Metrics";

    public function getTableDescription(): string
    {
        return "Other metrics that are derived from or closely related to this metric. For example, they could be metrics that measure a subset of what this metric is measuring, or a more context-specific version of this metric.";
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Section::make('Derived / Related Metric')
                    ->schema([
                        TextInput::make('title')
                            ->inlineLabel()
                            ->disabled(),
                        Textarea::make('definition')
                            ->inlineLabel()
                            ->disabled()
                    ]),
                Textarea::make('relation_notes')
                    ->label('Add any extra information about why this metric is associated to the other'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Metric'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Attach Metric')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select
                        ->createOptionForm([
                            TextInput::make('title')
                                ->inlineLabel()
                                ->required()
                                ->maxLength(255)
                                ->label('Name of metric'),
                            Textarea::make('definition')
                                ->inlineLabel(),
                            Textarea::make('notes')
                                ->inlineLabel()
                                ->label('Notes about this metric')
                                ->hint('This is about the metric entry itself, not about the link to the current metric.'),
                        ])
                        ->createOptionAction(fn(Action $action) => $action->modalHeading('Create Metric Entry'))
                        ->createOptionUsing(fn($data): string => Metric::create($data)->id)
                    )
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('relation_notes')->label('Add any extra information about why this metric is associated to the other'),
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
