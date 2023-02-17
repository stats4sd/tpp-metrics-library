<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use App\Models\Reference;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ComputationGuidanceRelationManager extends RelationManager
{
    protected static string $relationship = 'computationGuidance';
    protected static ?string $inverseRelationship = 'metrics';
    protected static ?string $recordTitleAttribute = 'name';

        protected bool $allowsDuplicates = true;


    protected static ?string $title = "5.d. Computation Guidance";

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

                Textarea::make('notes')
                    ->label('Add any extra information about how the reference provide computation or analysis guidance for this metric'),
                Hidden::make('reference_type')
                    ->default('computation guidance')
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
                Tables\Actions\AttachAction::make('Attach')
                    ->label('Attach')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select
                        ->multiple()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->label('Name of reference'),
                            TextInput::make('url')
                                ->label('Url'),
                            Textarea::make('notes')
                                ->label('Notes about this reference')
                                ->hint('Notes about the reference itself, not the link to the metric.'),
                        ])
                        ->createOptionUsing(fn($data): string => Reference::create($data)->id))
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('Notes')
                            ->label('Add any extra information about how the reference provide computation or analysis guidance for this metric'),
                        Hidden::make('reference_type')
                            ->default('computation guidance')
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
