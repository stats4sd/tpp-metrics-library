<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\DimensionResource;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

// ***********************
// NOTE - CURRENTLY UNUSED
// ***********************
class DimensionsRelationManager extends RelationManager
{
    protected static string $relationship = 'dimensions';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dimension')
                    ->schema([
                        TextInput::make('name')
                            ->inlineLabel()
                            ->disabled(),
                        Textarea::make('definition')
                            ->inlineLabel()
                            ->disabled()
                    ]),
                Textarea::make('relation_notes')
                    ->label('Add any extra information about why this metric is associated to the other'),
            ]);
    }

    public function table(Table $table): Table
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
                    ->preloadRecordSelect()
                    ->recordSelect(
                        fn (Select $select) => $select
                            ->multiple()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Enter the name of the new dimension'),
                                Textarea::make('notes')
                                    ->label('Add any notes about this dimension'),
                            ])
                    )
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('relation_notes')
                            ->hint('Add any extra information about how/why this metric is linked to this dimension.'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->url(fn ($record) => DimensionResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
