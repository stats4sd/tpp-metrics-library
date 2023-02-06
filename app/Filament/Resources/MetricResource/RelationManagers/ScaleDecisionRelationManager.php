<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ScaleDecisionRelationManager extends RelationManager
{
    protected static string $relationship = 'scaleDecision';
    protected static ?string $inverseRelationship = 'metricDecision';

    protected static ?string $recordTitleAttribute = 'name';

    public function getTableDescription(): string
    {
        return 'The scale(s) at which this metric can be used to make decisions.';
    }

    public function isTablePaginationEnabled(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \App\Filament\Form\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                \App\Filament\Form\Placeholder::make('Notes')
                    ->content('Add any extra information about how/why this metric can be used at this scale to make decisions'),
                \App\Filament\Form\Textarea::make('notes'),
                \App\Filament\Form\Checkbox::make('commonly_used')
                    ->label('Is the metric commonly used at this scale?'),
                \App\Filament\Form\Hidden::make('type')
                    ->default('decision making')


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
                Tables\Actions\CreateAction::make()
                    ->label('Create New Scale'),
                Tables\Actions\AttachAction::make('Attach Existing')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(\App\Filament\Form\Select $select) => $select->multiple())
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        \App\Filament\Form\Placeholder::make('Notes')
                            ->content('Add any extra information about how/why this metric can be used at this scale to make decisions'),
                        \App\Filament\Form\Textarea::make('notes'),
                        \App\Filament\Form\Checkbox::make('commonly_used')
                            ->label('Is the metric commonly used at this scale?'),
                        \App\Filament\Form\Hidden::make('type')
                            ->default('decision making'),
                    ]),
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
