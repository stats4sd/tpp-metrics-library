<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Scale;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Form\Components\Textarea;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\ScaleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Table\Actions\DeduplicateRecordsAction;
use App\Filament\Resources\ScaleResource\RelationManagers;

class ScaleResource extends Resource
{
    protected static ?string $model = Scale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'SCALES, USERS, USE CASES';
    protected static ?int $navigationSort = 31;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    TextInput::make('name')->required(),
                    Textarea::make('definition'),
                    Textarea::make('notes'),
                    Toggle::make('unreviewed_import')
                            ->label('Mark this imported record as reviewed')
                            ->visible(function (Model $record): bool {
                                $visible = $record->unreviewed_import==1;
                                return $visible;
                            })
                            ->offColor('success')
                            ->onColor('danger')
                            ->offIcon('heroicon-s-check')
                            ->onIcon('heroicon-s-exclamation-circle')
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
                DeduplicateRecordsAction::make(),
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
            'index' => Pages\ListScales::route('/'),
            'create' => Pages\CreateScale::route('/create'),
            'edit' => Pages\EditScale::route('/{record}/edit'),
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
