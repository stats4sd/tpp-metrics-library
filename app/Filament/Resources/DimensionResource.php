<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DimensionResource\Pages;
use App\Models\Dimension;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DimensionResource extends Resource
{
    protected static ?string $model = Dimension::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema(
            [
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name'),
                        Select::make('topic_id')->relationship('topic', 'name')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                                MarkdownEditor::make('description'),
                                MarkdownEditor::make('notes'),

                            ]),
                        MarkdownEditor::make('description'),
                        MarkdownEditor::make('notes'),
                    ]),
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDimensions::route('/'),
            'create' => Pages\CreateDimension::route('/create'),
            'edit' => Pages\EditDimension::route('/{record}/edit'),
        ];
    }
}
