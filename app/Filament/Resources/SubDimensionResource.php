<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\SubDimension;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Form\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Form\Components\Textarea;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubDimensionResource\Pages;
use App\Filament\Resources\SubDimensionResource\RelationManagers;

class SubDimensionResource extends Resource
{
    protected static ?string $model = SubDimension::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    Select::make('dimensionId')->relationship('dimension', 'name')->required(),
                    TextInput::make('name')->required(),
                    Textarea::make('definition'),
                    Textarea::make('notes'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('definition'),
                TextColumn::make('metrics_count')->counts('metrics'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
