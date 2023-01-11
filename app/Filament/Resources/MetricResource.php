<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetricResource\Pages;
use App\Models\Metric;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class MetricResource extends Resource
{
    protected static ?string $model = Metric::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('title'),
                        MarkdownEditor::make('description'),
                    ]),
                Section::make('Details')
                    ->schema([
                        TextInput::make('unit_of_measurement'),
                        TextInput::make('study_unit'),
                        MarkdownEditor::make('references'),
                    ]),
                Section::make('Additional Notes')
                    ->schema([
                    MarkdownEditor::make('notes')->helperText('any information that doesn\'t fit anywhere else should go here. At this stage the more details the better!'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(columns: [
                TextColumn::make('title'),
                TextColumn::make('updated_at')->dateTime(),
                TextColumn::make('unit_of_measurement'),
            ])
//            ->filters([
//                //
//            ])
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
            'index' => Pages\ListMetrics::route('/'),
            'create' => Pages\CreateMetric::route('/create'),
            'view' => Pages\ViewMetric::route('/{record}'),
            'edit' => Pages\EditMetric::route('/{record}/edit'),
        ];
    }
}
