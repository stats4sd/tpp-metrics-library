<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Models\MetricUser;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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

    protected static ?string $title = "6.a.c. Impacted Users";

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
                Textarea::make('notes')
                    ->label('Add any extra information about how/why this type of user is a "collector" of this metric.'),
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
                        ->createOptionUsing(fn($data): string => MetricUser::create($data)->id))
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('notes')
                            ->label('Add any extra information about how/why this type of user is a "collector" of this metric.'),
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
