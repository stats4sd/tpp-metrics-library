<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Geography;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Form\Components\Textarea;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\GeographyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GeographyResource\RelationManagers;

class GeographyResource extends Resource
{
    protected static ?string $model = Geography::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
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
                TextColumn::make('notes'),
                TextColumn::make('metrics_count')->counts('metrics')->sortable(),
                IconColumn::make('unreviewed_import')
                            ->options(['heroicon-o-exclamation-circle' => fn($state): bool => (bool)$state])
                            ->color('danger')
                            ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('unreviewed_import')
                                        ->query(fn(Builder $query): Builder => $query->where('unreviewed_import', true))
                                        ->label('Unreviewed imported records'),
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
            'index' => Pages\ListGeographies::route('/'),
            'create' => Pages\CreateGeography::route('/create'),
            'edit' => Pages\EditGeography::route('/{record}/edit'),
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
