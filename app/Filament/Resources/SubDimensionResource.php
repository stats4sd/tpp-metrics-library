<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubDimensionResource\Pages;
use App\Filament\Resources\SubDimensionResource\RelationManagers;
use App\Models\SubDimension;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubDimensionResource extends Resource
{
    protected static ?string $model = SubDimension::class;

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
            'index' => Pages\ListSubDimensions::route('/'),
            'create' => Pages\CreateSubDimension::route('/create'),
            'edit' => Pages\EditSubDimension::route('/{record}/edit'),
        ];
    }    
}
