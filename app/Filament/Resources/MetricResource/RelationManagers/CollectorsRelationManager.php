<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Models\MetricUser;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Placeholder::make('Notes')
                    ->content('Add any extra information about how/why this type of user is a "collector" of this metric.'),
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
                                ->label('Type of user'),
                            Textarea::make('definition')
                                ->label('Definition of this user type'),
                            Textarea::make('notes')
                                ->label('Notes about this type of user')
                                ->hint('Not specifically about why they are linked to this metric'),
                        ])
                        ->createOptionUsing(fn($data): string => MetricUser::create($data)->id))
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Placeholder::make('Notes')
                            ->content('Add any extra information about how/why this type of user is a "collector" of this metric.'),
                        Textarea::make('notes'),
                        Hidden::make('type')
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
