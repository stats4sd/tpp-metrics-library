<?php

namespace App\Filament\Resources\AltNameResource\RelationManagers;

use App\Filament\Resources\MetricResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class MetricRelationManager extends RelationManager
{
    protected static string $relationship = 'metric';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return MetricResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make(),
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
