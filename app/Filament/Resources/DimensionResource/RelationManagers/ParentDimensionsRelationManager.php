<?php

namespace App\Filament\Resources\DimensionResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\MetricResource;
use ParentDimension\Pages\CreateParentDimension;
use Filament\Resources\RelationManagers\RelationManager;

class ParentDimensionsRelationManager extends RelationManager
{
    protected static string $relationship = 'parentDimensions';

    protected static ?string $recordTitleAttribute = 'name';

    public function getTableHeading(): string
    {
        return 'Parent dimensions for ' . $this->ownerRecord->name;
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dimension')
                    ->schema([
                        TextInput::make('name')
                            ->inlineLabel()
                            ->disabled(),
                    ]),

                Textarea::make('relation_notes')
                    ->label('Add any extra information about why this dimension is parent to the dimension')
                    ->columnSpan(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->inverseRelationship('parentDimensions')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
