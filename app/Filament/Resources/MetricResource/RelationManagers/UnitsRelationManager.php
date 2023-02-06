<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    protected static ?string $recordTitleAttribute = 'label';


            public function getTableDescription(): string
    {
        return 'The units in which the metric is usually expressed';
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
                                \App\Filament\Form\TextInput::make('symbol')
            ->required()
            ->maxLength(255),
                                \App\Filament\Form\Placeholder::make('Notes')
                    ->content('Add any extra information about the relationship between this unit and the metric'),
                \App\Filament\Form\Textarea::make('notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
           ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create new Unit'),
                Tables\Actions\AttachAction::make('Attach Existing')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(\App\Filament\Form\Select $select) => $select->multiple())
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        \App\Filament\Form\Placeholder::make('Notes')
                    ->content('Add any extra information about the relationship between this unit and the metric'),
                \App\Filament\Form\Textarea::make('notes'),
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
