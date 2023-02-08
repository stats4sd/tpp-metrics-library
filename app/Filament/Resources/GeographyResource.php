<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeographyResource\Pages;
use App\Filament\Resources\GeographyResource\RelationManagers;
use App\Models\Geography;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GeographyResource extends Resource
{
    protected static ?string $model = Geography::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListGeographies::route('/'),
            'create' => Pages\CreateGeography::route('/create'),
            'edit' => Pages\EditGeography::route('/{record}/edit'),
        ];
    }    
}
