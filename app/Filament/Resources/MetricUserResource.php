<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\MetricUser;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MetricUserResource\Pages;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Resources\MetricUserResource\RelationManagers;
use App\Filament\Resources\MetricResource\RelationManagers\MetricUserMetricsRelationManager;

class MetricUserResource extends Resource
{
    protected static ?string $model = MetricUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'SCALES, USERS, USE CASES';
    protected static ?int $navigationSort = 32;

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

            RelationGroup::make('Metrics', [
                MetricUserMetricsRelationManager::class,
            ]),

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMetricUsers::route('/'),
            'create' => Pages\CreateMetricUser::route('/create'),
            'edit' => Pages\EditMetricUser::route('/{record}/edit'),
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
