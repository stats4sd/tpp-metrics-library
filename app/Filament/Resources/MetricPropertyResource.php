<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetricPropertyResource\Pages;
use App\Filament\Resources\MetricPropertyResource\RelationManagers;
use App\Models\MetricProperty;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class MetricPropertyResource extends Resource
{
    protected static ?string $model = MetricProperty::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name'),
                        MarkdownEditor::make('description'),
                        MarkdownEditor::make('notes'),
                    ]),
            ]);
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
            'index' => Pages\ListMetricProperties::route('/'),
            'create' => Pages\CreateMetricProperty::route('/create'),
            'edit' => Pages\EditMetricProperty::route('/{record}/edit'),
        ];
    }
}
