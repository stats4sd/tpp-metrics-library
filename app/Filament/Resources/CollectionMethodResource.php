<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Property;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\PropertyOption;
use App\Models\CollectionMethod;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Form\Components\Textarea;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\CollectionMethodResource\Pages;
use App\Filament\Resources\CollectionMethodResource\RelationManagers;

class CollectionMethodResource extends Resource
{
    protected static ?string $model = CollectionMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('methods-tabs')
                    ->columnSpanFull()
                    ->schema([

                        Tab::make('Core Info')
                        ->schema([
                            TextInput::make('title')->required(),
                            Textarea::make('description'),
                            Textarea::make('pros_cons')->label('Pros/Cons'),
                            Textarea::make('notes'),
                        ]),

                        Tab::make('Properties')
                            ->hiddenOn(Pages\CreateCollectionMethod::class)
                            ->schema(function () {
                                $props = Property::where('default_type', '=', CollectionMethod::class)->get();

                                return $props->map(function ($property) {

                                    // a single property can include 1 or 2 fields (a select and a free-text).
                                    $components = [];


                                    if ($property->select_options) {

                                        $component = Select::make('property_' . $property->id)
                                            ->label($property->code . ' - ' . $property->name)
                                            ->inlineLabel()
                                            ->hint($property->definition)
                                            ->multiple($property->select_multiple)
                                            ->options(fn() => PropertyOption::where('property_id', '=', $property->id)
                                                ->pluck('name', 'id')->toArray());
                                            // ->suffixAction(self::makeDiscussionPointAction());

                                        if ($property->editable_options) {
                                            $component = $component->createOptionForm([
                                                TextInput::make('name')
                                                    ->label("Enter the name of the new option for {$property->name}"),
                                                Textarea::make('notes')
                                                    ->label('Add any notes about this option.'),
                                                Hidden::make('property_id')
                                                    ->default($property->id)
                                            ])
                                                ->createOptionUsing(function ($data): ?string {
                                                    return (string)PropertyOption::create($data)->id;
                                                });
                                        }

                                        $components[] = $component;

                                    }

                                    if ($property->free_text) {

                                        $label = $property->code . ' - ' . $property->name;
                                        $hint = $property->definition;
                                        // change some field attributes if a select field also exists
                                        if ($property->select_options) {
                                            $label = $label . ': Additional Notes';
                                            $hint = ''; // no need to repeat the hint
                                        }

                                        $component = Textarea::make('property_notes_' . $property->id)
                                            ->label($label)
                                            ->inlineLabel()
                                            ->hint($hint);

                                        if (!$property->select_options) {
                                            $component = $component->suffixAction(self::makeDiscussionPointAction());
                                        }

                                        $components[] = $component;
                                    }


                                    return $components;

                                })->flatten()->toArray();
                            })
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('description'),
                TextColumn::make('pros_cons')->label('Pros/Cons'),
                TextColumn::make('metrics_count')->counts('metrics')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('unreviewed_import')
                                        ->query(fn(Builder $query): Builder => $query->where('unreviewed_import', true))
                                        ->label('Unreviewed imported records'),
                TrashedFilter::make(),
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
            'index' => Pages\ListCollectionMethods::route('/'),
            'create' => Pages\CreateCollectionMethod::route('/create'),
            'edit' => Pages\EditCollectionMethod::route('/{record}/edit'),
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
