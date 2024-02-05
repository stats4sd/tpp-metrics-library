<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubDimensionResource\Pages;
use App\Filament\Resources\SubDimensionResource\RelationManagers;
use App\Models\SubDimension;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubDimensionResource extends Resource
{
    protected static ?string $model = SubDimension::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'TOPICS';
    protected static ?int $navigationSort = 23;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    Select::make('dimension_id')->relationship('dimension', 'name')->required(),
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
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('definition'),
                TextColumn::make('metrics_count')->counts('metrics')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
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

    public static function getEloquentQuery(): Builder
    {
    return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }

}
