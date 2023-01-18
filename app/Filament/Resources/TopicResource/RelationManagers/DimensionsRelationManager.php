<?php

namespace App\Filament\Resources\TopicResource\RelationManagers;

use App\Filament\Resources\DimensionResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class DimensionsRelationManager extends RelationManager
{
    protected static string $relationship = 'dimensions';
    protected static ?string $inverseRelationship = 'topic';
    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Form $form): Form
    {
        // TODO: figure out how to distinguish between dimension notes and pivot notes...
        // ... do we call pivot notes something else?
        return DimensionResource::form($form);
    }

    public static function table(Table $table): Table
    {
        //$table->getHeader = fn() => 'title';

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make()
                    ->preloadRecordSelect(),
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
