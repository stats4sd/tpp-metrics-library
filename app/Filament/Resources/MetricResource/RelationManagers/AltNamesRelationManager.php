<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\AltNameResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class AltNamesRelationManager extends RelationManager
{
    protected static string $relationship = 'altNames';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return AltNameResource::form($form);
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
