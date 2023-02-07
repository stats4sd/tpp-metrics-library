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

class ImpactedByRelationManager extends RelationManager
{
    protected static string $relationship = 'impactedBy';
    protected static ?string $inverseRelationship = 'metricImpactedBy';
    protected static ?string $recordTitleAttribute = 'name';

    public function getTableDescription(): string
    {
        return 'Who does the results of this metric impact / affect?';
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
                    ->content('Any extra information about how/why this type of user is impacted by the results of this metric'),
                Textarea::make('notes'),
                Hidden::make('type')
                    ->default('impacted by'),
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
            ->headerActions(actions: [
                Tables\Actions\CreateAction::make()
                    ->label('Create New'),
                Tables\Actions\AttachAction::make()
                    ->label('Attach Existing')
                    ->preloadRecordSelect()
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Placeholder::make('Notes')
                            ->content('Any extra information about how/why this type of user is impacted by the results of this metric'),
                        Textarea::make('notes'),
                        Hidden::make('type')
                            ->default('impacted by'),
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
