<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscussionPointResource\Pages;
use App\Filament\Resources\DiscussionPointResource\RelationManagers;
use App\Models\DiscussionPoint;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class DiscussionPointResource extends Resource
{
    protected static ?string $model = DiscussionPoint::class;

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
                TextColumn::make('subject_type_label'),
                TextColumn::make('subject.title'),
                TextColumn::make('property'),
                TextColumn::make('property_value.name'),
                TextColumn::make('user.name'),
                TextColumn::make('notes'),
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
            'index' => Pages\ListDiscussionPoints::route('/'),
            'create' => Pages\CreateDiscussionPoint::route('/create'),
            'edit' => Pages\EditDiscussionPoint::route('/{record}/edit'),
        ];
    }
}
