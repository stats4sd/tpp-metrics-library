<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AltNameResource\Pages;
use App\Filament\Resources\AltNameResource\RelationManagers;
use App\Models\AltName;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class AltNameResource extends Resource
{
    protected static ?string $model = AltName::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema(
            [
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name'),
                        MarkdownEditor::make('notes'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListAltNames::route('/'),
            'create' => Pages\CreateAltName::route('/create'),
            'edit' => Pages\EditAltName::route('/{record}/edit'),
        ];
    }
}
