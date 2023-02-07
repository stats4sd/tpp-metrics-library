<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class DecisionMakerRelationManager extends RelationManager
{
    protected static string $relationship = 'decisionMakers';
    protected static ?string $inverseRelationship = 'metricDecisionMakers';
    protected static ?string $recordTitleAttribute = 'name';

//    protected bool $allowsDuplicates = true;

    public function getTableDescription(): string
    {
        return 'What sort of users would be making decisions based on this metric?';
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
                Placeholder::make('Notes')
                    ->content('Add any extra information about how/why this type of user is a "decision-maker" for this metric'),
                Textarea::make('notes'),
                Hidden::make('type')
                    ->default('decision maker'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Category of user'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Creeate New'),
                Tables\Actions\AttachAction::make()
//                    ->recordSelectOptionsQuery(fn(Builder $query) => $query->whereDoesntHave('type', '!=', 'decision maker'))
                    ->label('Attach Existing')
                    ->preloadRecordSelect()
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Placeholder::make('Notes')
                            ->content('Add any extra information about how/why this type of user is a "decision-maker" for this metric'),
                        Textarea::make('notes'),
                        Hidden::make('type')
                            ->default('decision maker'),
                    ])
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
