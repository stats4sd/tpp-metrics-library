<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class FrameworksRelationManager extends RelationManager
{
    protected static string $relationship = 'frameworks';

    protected static ?string $recordTitleAttribute = 'name';

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
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                                Placeholder::make('Notes')
                    ->content('Add any extra information about the relationship between this framework and the metric'),
                Textarea::make('notes'),
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
                Tables\Actions\CreateAction::make()
                    ->label('Create new Framework'),
                Tables\Actions\AttachAction::make('Attach Existing')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select->multiple())
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Placeholder::make('Notes')
                    ->content('Add any extra information about the relationship between this framework and the metric'),
                Textarea::make('notes'),
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
