<?php

namespace App\Filament\Resources;

use App\Filament\Form\Checkbox;
use App\Filament\Form\Repeater;
use App\Filament\Form\Section;
use App\Filament\Form\Select;
use App\Filament\Form\Textarea;
use App\Filament\Form\TextInput;
use App\Filament\Resources\PropertyResource\Pages;
use App\Filament\Resources\PropertyResource\RelationManagers;
use App\Models\CollectionMethod;
use App\Models\Metric;
use App\Models\Property;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Core Info')
                    ->schema([
                        TextInput::make('code')->required(),
                        TextInput::make('name')->required(),
                        Textarea::make('definition'),
                    ]),
                Section::make('Values')
                    ->schema([
                        Checkbox::make('free_text')
                            ->label('Is this a free text property?')
                            ->reactive(),
                        Checkbox::make('select_multiple')
                            ->label('Can users choose multiple options for this property?')
                            ->hidden(fn(callable $get) => $get('free_text') === true),
                        Checkbox::make('editable_options')
                            ->label('Can users add new options for this property?')
                            ->hidden(fn(callable $get) => $get('free_text') === true),
                        Repeater::make('propertyOptions')
                            ->defaultItems(0)
                            ->relationship('propertyOptions')
                            ->schema([
                                TextInput::make('name'),
                                Textarea::make('notes'),
                            ])
                            ->hidden(fn(callable $get) => $get('free_text') === true),
                        Select::make('default_type')
                            ->label('Should this be automatically shown for new Metrics or Collection Methods?')
                            ->options([
                                Metric::class => Metric::class,
                                CollectionMethod::class => CollectionMethod::class,
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('select_multiple')->boolean(),
                Tables\Columns\IconColumn::make('free_text')->boolean(),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
