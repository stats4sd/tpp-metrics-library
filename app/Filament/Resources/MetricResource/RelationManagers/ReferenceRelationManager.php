<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Models\Reference;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ReferenceRelationManager extends RelationManager
{
    protected static string $relationship = 'references';
    protected static ?string $inverseRelationship = 'referencable';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = "7. References";

    public function getTableDescription(): string
    {
        return 'Guidance / protocols on the analysis or computation of the metric';
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
                Textarea::make('notes'),
                Hidden::make('type')
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
                Tables\Actions\AttachAction::make()
                    ->label('Attach')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->label('Name of data source'),
                            TextInput::make('url')
                                ->label('Url of the source'),
                            Textarea::make('notes')
                                ->label('Notes about this type of user')
                                ->hint('Not specifically about why they are linked to this metric'),
                        ])
                        ->createOptionUsing(fn($data): string => Reference::create($data)->id))
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Placeholder::make('Notes')
                            ->content('Add any extra information about how/why this type of user is a "collector" of this metric.'),
                        Textarea::make('notes'),
                        Hidden::make('type')
                            ->default('data source')
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
