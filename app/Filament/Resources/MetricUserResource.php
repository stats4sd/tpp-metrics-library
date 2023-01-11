<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetricUserResource\Pages;
use App\Filament\Resources\MetricUserResource\RelationManagers;
use App\Models\MetricUser;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class MetricUserResource extends Resource
{
    protected static ?string $model = MetricUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema(
            [
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
                //
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
            'index' => Pages\ListMetricUsers::route('/'),
            'create' => Pages\CreateMetricUser::route('/create'),
            'edit' => Pages\EditMetricUser::route('/{record}/edit'),
        ];
    }
}
