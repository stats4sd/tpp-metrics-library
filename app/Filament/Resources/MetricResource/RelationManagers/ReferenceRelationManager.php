<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Tables;
use App\Models\Reference;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use App\Filament\Form\Components\Textarea;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use Filament\Resources\RelationManagers\RelationManager;

class ReferenceRelationManager extends RelationManager
{
    protected static string $relationship = 'references';
    protected static ?string $inverseRelationship = 'metrics';
    protected static ?string $recordTitleAttribute = 'name';

    protected bool $allowsDuplicates = true;

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
                    ->label('Add any extra information about how this reference relates to the metric'),
                Hidden::make('reference_type')
                    ->default('reference')
            ]);
    }

    public static function table(Table $table): Table
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
                            ->default('reference')
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
