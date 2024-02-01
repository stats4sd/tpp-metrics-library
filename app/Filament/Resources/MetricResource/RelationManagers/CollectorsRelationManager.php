<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use App\Models\MetricUser;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class CollectorsRelationManager extends RelationManager
{
    protected static string $relationship = 'collectors';
    protected static ?string $inverseRelationship = 'metricCollectors';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = "6.a.a. Collectors";

    public function getTableDescription(): string
    {
        return 'What sort of users would be involved in collecting the data to calculate this metric?';
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
                Section::make('User Type')
                    ->schema([
                        TextInput::make('name')
                            ->inlineLabel()
                            ->disabled(),
                        Textarea::make('definition')
                            ->inlineLabel()
                            ->disabled()
                    ]),
                Textarea::make('relation_notes')
                    ->label('Add any extra information about how/why this type of user is a "collector" of this metric.'),
                Hidden::make('type')
                    ->default('collector')
            ]);
    }

    public function table(Table $table): Table
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
                                ->inlineLabel()
                                ->required()
                                ->maxLength(255)
                                ->label('Type of user'),
                            Textarea::make('definition')
                                ->inlineLabel()
                                ->label('Definition of this user type'),
                            Textarea::make('notes')
                                ->inlineLabel()
                                ->label('Notes about this type of user')
                                ->hint('Not specifically about why they are linked to this metric'),
                        ])
                        ->createOptionAction(fn(Action $action) => $action->modalHeading('Create User Entry'))
                        ->createOptionUsing(fn($data): string => MetricUser::create($data)->id)
                    )
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('relation_notes')
                            ->label('Add any extra information about how/why this type of user is a "collector" of this metric.'),
                        Hidden::make('type')
                            ->default('collector')
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading('Edit link between user type and metric'),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
