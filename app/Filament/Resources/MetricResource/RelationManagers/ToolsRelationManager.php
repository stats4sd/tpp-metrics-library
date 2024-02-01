<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use App\Models\Tool;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class ToolsRelationManager extends RelationManager
{
    protected static string $relationship = 'tools';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = '0.g. Tools';

    public function getTableDescription(): string
    {
        return 'Assessment tools that that use this metric.';
    }

    public function isTablePaginationEnabled(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Section::make('Tool')
                    ->schema([
                        TextInput::make('name')
                            ->inlineLabel()
                            ->disabled(),
                        Textarea::make('definition')
                            ->inlineLabel()
                            ->disabled()
                    ]),
                Textarea::make('relation_notes')
                    ->label('Add any extra information about the link between this tool and this metric.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tool'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make('Attach')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select
                        ->createOptionForm([
                            TextInput::make('name')
                                ->inlineLabel()
                                ->required()
                                ->maxLength(255)
                                ->label('Name of the tool'),
                            Textarea::make('definition')
                                ->inlineLabel()
                                ->label('Definition or description of the tool')
                                ->hint('This could include a link to where you can find more information about the tool.'),
                            Textarea::make('notes')
                                ->inlineLabel()
                                ->label('Notes about the tool')
                                ->hint('These are notes about the tool itself, not about the relationship to the metric.')
                        ])
                        ->createOptionAction(fn(Action $action) => $action->modalHeading('Create Tool Entry'))
                        ->createOptionUsing(fn($data): string => Tool::create($data)->id)
                    )
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('relation_notes')
                            ->label('Add any extra information about the relationship between this assessment tool and the metric.'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit link between the tool and the metric'),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
