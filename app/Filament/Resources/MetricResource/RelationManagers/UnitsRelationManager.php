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

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';
    protected static ?string $recordTitleAttribute = 'label';

    protected static ?string $title = "1.d. Units of Measure";

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
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('symbol')
                    ->required()
                    ->maxLength(255),
                Placeholder::make('Notes')
        ->content('Add any extra information about the relationship between this unit and the metric'),
                Textarea::make('notes'),
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
                Tables\Actions\AttachAction::make('Attach Existing')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select->multiple())
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
        $action->getRecordSelect(),
        Placeholder::make('Notes')
        ->content('Add any extra information about the relationship between this unit and the metric'),
                Textarea::make('notes'),
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
