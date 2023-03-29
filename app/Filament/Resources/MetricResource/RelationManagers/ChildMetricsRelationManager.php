<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Tables;
use App\Models\Metric;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Filament\Form\Components\Textarea;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use Filament\Resources\RelationManagers\RelationManager;

class ChildMetricsRelationManager extends RelationManager
{
    protected static string $relationship = 'childMetrics';
    protected static ?string $inverseRelationship = 'inverseChildMetrics';

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
                        Textarea::make('notes')->label('Add any extra information about why this metric is associated to the other'),
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
