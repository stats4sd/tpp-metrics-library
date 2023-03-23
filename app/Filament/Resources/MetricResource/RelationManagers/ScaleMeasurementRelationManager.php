<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use App\Models\Scale;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ScaleMeasurementRelationManager extends RelationManager
{
    protected static string $relationship = 'scaleMeasurement';
    protected static ?string $inverseRelationship = 'metricMeasurement';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = "1.c. Scale of Measurement";

    public function getTableDescription(): string
    {
        return 'The scale(s) at which this metric is measured.';
    }

    public function isTablePaginationEnabled(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Section::make('Scale')
                    ->schema([

                        TextInput::make('name')
                            ->inlineLabel()
                            ->disabled(),
                        Textarea::make('definition')
                            ->inlineLabel()
                            ->disabled(),
                    ]),
                Textarea::make('notes')
                    ->label('Add any extra information about how/why this metric can be used at this scale to make decisions'),
                Checkbox::make('commonly_used')
                    ->label('Is the metric commonly used at this scale?'),
                Hidden::make('type')
                    ->default('measurement'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Scale'),
                Tables\Columns\IconColumn::make('commonly_used')
                    ->boolean()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make('Attach')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->inlineLabel()
                                ->maxLength(255),
                            Textarea::make('definition')
                                ->inlineLabel(),
                            Textarea::make('notes')
                                ->inlineLabel()
                                ->label('Notes about this scale')
                                ->hint('This is not about how the scale relates to the current metric, but about the definition of the scale itself.'),
                        ])
                        ->createOptionAction(fn(Action $action) => $action->modalHeading('Create Scale Entry'))
                        ->createOptionUsing(fn(array $data) => Scale::create($data)->id)
                    )
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->autofocus(false),
                        Textarea::make('notes')
                            ->label('Add any extra information about how/why this metric can be used at this scale to make decisions'),
                        Checkbox::make('commonly_used')
                            ->label('Is the metric commonly used at this scale?'),
                        Hidden::make('type')
                            ->default('measurement'),

                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Link between scale and metric'),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
