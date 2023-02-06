<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class CollectorsRelationManager extends RelationManager
{
    protected static string $relationship = 'collectors';
    protected static ?string $inverseRelationship = 'metricCollectors';
    protected static ?string $recordTitleAttribute = 'name';

    public function getTableDescription(): string
    {
        return 'What sort of users would be involved in collecting the data to calculate this metric?';
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
                    ->content('Add any extra information about how/why this type of user is a "collector" of this metric.'),
                \App\Filament\Form\Textarea::make('notes'),
                \App\Filament\Form\Hidden::make('type')
                    ->default('collector')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Category of user')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create New'),
                Tables\Actions\AttachAction::make()
                    ->label('Attach Existing')
                    ->preloadRecordSelect()
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        \App\Filament\Form\Placeholder::make('Notes')
                            ->content('Add any extra information about how/why this type of user is a "collector" of this metric.'),
                        \App\Filament\Form\Textarea::make('notes'),
                        \App\Filament\Form\Hidden::make('type')
                            ->default('collector')
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
