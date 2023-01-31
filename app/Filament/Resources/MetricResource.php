<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetricResource\Pages;
use App\Filament\Resources\MetricResource\RelationManagers\DimensionsRelationManager;
use App\Models\Metric;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class MetricResource extends Resource
{
    protected static ?string $model = Metric::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Name(s)')
                    ->schema([

                        /** 0.a Title */
                        TextInput::make('title'),

                        /** 0.b Alt Names */
                        Repeater::make('altNames')
                            ->label('Alternative Names')
                            ->relationship()
                            ->schema([
                                TextInput::make('name'),
                                Textarea::make('notes')
                                    ->helperText('E.g. Where is this name used? Who uses it? Is it a common name, or only occasionally used?'),
                            ])
                        ->createItemButtonLabel('Add new name'),
                    ]),
                Section::make('Context')
                    ->schema([
                        CheckboxList::make('topics')
                            ->relationship('topics', 'name')
                            ->columns(2),
                    ]),
                Section::make('Links')

            ]);

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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DimensionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMetrics::route('/'),
            'view' => Pages\ViewMetric::route('/{record}'),
            'create' => Pages\CreateMetric::route('/create'),
            'edit' => Pages\EditMetric::route('/{record}/edit'),
        ];
    }
}
