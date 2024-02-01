<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use App\Models\Reference;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class DataSourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'dataSources';
    protected static ?string $inverseRelationship = 'metrics';
    protected static ?string $recordTitleAttribute = 'name';

        protected bool $allowsDuplicates = true;


    protected static ?string $title = "4.c. Data Sources";

    public function getTableDescription(): string
    {
        return 'Existing data sources for this metric';
    }

    public function isTablePaginationEnabled(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Reference')
                    ->schema([
                        TextInput::make('name')
                            ->inlineLabel()
                            ->disabled(),
                        TextInput::make('url')
                            ->inlineLabel()
                            ->disabled(),
                    ]),

                Textarea::make('relation_notes')
                    ->label('Add any extra information about how this reference is a data source for the metric.'),
                Hidden::make('reference_type')
                    ->default('data source')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Reference')
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
                                ->inlineLabel()
                                ->required()
                                ->label('Name of reference'),
                            TextInput::make('url')
                                ->inlineLabel()
                                ->label('Url'),
                            Textarea::make('notes')
                                ->inlineLabel()
                                ->label('Notes about this reference')
                                ->hint('Notes about the reference itself, not the link to the metric.'),
                        ])
                        ->createOptionAction(fn(Action $action) => $action->modalHeading('Create Reference Entry'))
                        ->createOptionUsing(fn($data): string => Reference::create($data)->id))
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('relation_notes')
                            ->label('Add any extra information about how this reference relates to the metric'),
                        Hidden::make('reference_type')
                            ->default('data source')
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit link between metric and reference'),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
