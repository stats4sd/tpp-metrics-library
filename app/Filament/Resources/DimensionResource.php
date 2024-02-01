<?php

namespace App\Filament\Resources;

use App\Filament\Form\Components\Textarea;
use App\Filament\Resources\DimensionResource\Pages;
use App\Filament\Resources\DimensionResource\RelationManagers;
use App\Filament\Table\Actions\DeduplicateRecordsAction;
use App\Models\Dimension;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DimensionResource extends Resource
{
    protected static ?string $model = Dimension::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    Select::make('topic_id')->relationship('topic', 'name')->required(),
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
            'index' => Pages\ListDimensions::route('/'),
            'create' => Pages\CreateDimension::route('/create'),
            'edit' => Pages\EditDimension::route('/{record}/edit'),
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
