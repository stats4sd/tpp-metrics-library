<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use App\Models\CollectionMethod;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class CollectionMethodsRelationManager extends RelationManager
{
    protected static string $relationship = 'collectionMethods';
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = '4.a. Collection Methods';

    public function getTableDescription(): string
    {
        return 'Collection methods for this metric.';
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
                Section::make('Collection Method')
                    ->schema([
                        TextInput::make('title')
                            ->inlineLabel()
                            ->disabled(),
                        Textarea::make('description')
                            ->inlineLabel()
                            ->disabled()
                    ]),
                Textarea::make('relation_notes')
                    ->label('Add any extra information about the link between this collection method and this metric.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Collection Method'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make('Attach')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select
                        ->createOptionForm([
                            TextInput::make('title')
                                ->inlineLabel()
                                ->required()
                                ->maxLength(255)
                                ->label('Name of the collection method'),
                            Textarea::make('description')
                                ->inlineLabel()
                                ->label('Definition or description of the collection method')
                                ->hint('This could include a link to where you can find more information about the collection method.'),
                            Textarea::make('notes')
                                ->inlineLabel()
                                ->label('Notes about the collection method')
                                ->hint('These are notes about the collection method itself, not about the relationship to the metric.')
                        ])
                        ->createOptionAction(fn(Action $action) => $action->modalHeading('Create Collection Method Entry'))
                        ->createOptionUsing(fn($data): string => CollectionMethod::create($data)->id)
                    )
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('relation_notes')
                            ->label('Add any extra information about the relationship between this collection method and the metric.'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit link between the collection method and the metric'),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
