<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DimensionResource\Pages;
use App\Filament\Resources\DimensionResource\RelationManagers\MetricRelationManager;
use App\Models\Dimension;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DimensionResource extends Resource
{
    protected static ?string $model = Dimension::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('topic_id')
                    ->relationship('topic', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('topic.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            MetricRelationManager::class,
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
