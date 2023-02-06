<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class FrameworksRelationManager extends RelationManager
{
    protected static string $relationship = 'frameworks';

    protected static ?string $recordTitleAttribute = 'name';

        public function getTableDescription(): string
    {
        return 'Frameworks that this metric relates to.';
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
                    ->content('Add any extra information about the relationship between this framework and the metric'),
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
                    ->label('Create new Framework'),
                Tables\Actions\AttachAction::make('Attach Existing')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(\App\Filament\Form\Select $select) => $select->multiple())
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        \App\Filament\Form\Placeholder::make('Notes')
                    ->content('Add any extra information about the relationship between this framework and the metric'),
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
