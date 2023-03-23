<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Tables;
use App\Models\Framework;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use App\Filament\Form\Components\Textarea;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use Filament\Resources\RelationManagers\RelationManager;

class FrameworksRelationManager extends RelationManager
{
    protected static string $relationship = 'frameworks';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = '0.h. Frameworks';


    public function getTableDescription(): string
    {
        return 'Frameworks that this metric relates to.';
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
                Section::make('Framework')
                    ->schema([
                        TextInput::make('name')
                            ->inlineLabel()
                            ->disabled(),
                        Textarea::make('definition')
                            ->inlineLabel()
                            ->disabled()
                    ]),
                Textarea::make('notes')
                    ->label('Add any extra information about the relationship between this framework and this metric.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Framework')
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
                                ->label('Framework Name'),
                            Textarea::make('definition')
                                ->inlineLabel()
                                ->label('Definition or description of this framework.')
                            ->hint('This could include a link to more information about the framework'),
                            Textarea::make('notes')
                                ->inlineLabel()
                                ->label('Notes about this framework')
                                ->hint('Not specifically about why they are linked to this metric'),
                        ])
                        ->createOptionAction(fn(Action $action) => $action->modalHeading('Create Framework Entry'))
                        ->createOptionUsing(fn($data): string => Framework::create($data)->id)
                    )
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('notes')
                            ->label('Add any extra information about the relationship between this framework and the metric'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading('Edit link between framework and metric'),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
