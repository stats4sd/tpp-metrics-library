<?php

namespace App\Filament\Resources;

use App\Filament\Form\Components\CheckboxList;
use App\Filament\Form\Components\Textarea;
use App\Filament\Resources\MetricResource\Pages;
use App\Filament\Resources\MetricResource\RelationManagers\CollectorsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ComplimentaryMetricsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\DecisionMakerRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\DimensionsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\FarmingSystemsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\FrameworksRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\GeographiesRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ImpactedByRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ScaleDecisionRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ScaleMeasurementRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ScaleReportingRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\SubDimensionsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ToolsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\TopicsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\UnitsRelationManager;
use App\Filament\Resources\Traits\HasDiscussionPoints;
use App\Models\Dimension;
use App\Models\Metric;
use App\Models\Property;
use App\Models\PropertyOption;
use App\Models\SubDimension;
use App\Models\Topic;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class MetricResource extends Resource
{
    use HasDiscussionPoints;

    protected static ?string $model = Metric::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Basic Info')
                    ->schema([

                        /** 0.a Title */
                        TextInput::make('title')
                            ->required()
                            ->inlineLabel()
                            ->helperText('The identifying name for the metric')
                            ->suffixAction(self::makeDiscussionPointAction()),

                        Placeholder::make('-'),

                        /** 0.b Alt Names */
                        \App\Filament\Form\Components\Repeater::make('altNames')
                            ->defaultItems(0)
                            ->collapsed()
                            ->label('Alternative Names')
                            ->relationship()
                            ->schema([
                                TextInput::make('name'),
                                Textarea::make('notes')
                                    ->helperText('E.g. Where is this name used? Who uses it? Is it a common name, or only occasionally used?'),
                            ])
                            ->createItemButtonLabel('Add new name')
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? '(new name)')
                            ->suffixAction(self::makeDiscussionPointAction()),

                        /** 0.i Developer */
                        Select::make('developer_id')
                            ->relationship('developer', 'name')
                            ->createOptionForm([
                                TextInput::make('name'),
                                Textarea::make('notes')
                            ])
                            ->suffixAction(self::makeDiscussionPointAction()),

                    ]),

                Section::make('Topics and Dimensions')
                    ->schema([

                        /** 0.c Topics */
                        CheckboxList::make('topics')
                            ->relationship('topics', 'name')
                            ->columns(2)
                            ->options(Topic::orderBy('id')->get()->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->suffix('Add discussion point')
                            ->suffixAction(self::makeDiscussionPointAction()),

                        /** 0.d Dimensions */
                        Placeholder::make('Dimensions')
                            ->content('Select one or more Topics to see available dimensions')
                            ->hidden(fn(callable $get) => $get('topics') !== []),

                        CheckboxList::make('dimensions')
                            ->hidden(fn(callable $get) => $get('topics') === [])
                            ->relationship('dimensions', 'name')
                            ->columns(3)
                            ->options(fn(callable $get) => Dimension::whereIn('topic_id', $get('topics'))
                                ->orderBy('topic_id')
                                ->get()
                                ->pluck('name', 'id')->toArray()
                            )
                            ->reactive()
                            ->suffix('Add discussion point')
                            ->suffixAction(self::makeDiscussionPointAction()),

                        /** 0.e sub-dimensions */
                        Placeholder::make('Sub Dimensions')
                            ->content('Select one or more Dimensions before selecting sub-dimensions')
                            ->hidden(fn(callable $get) => $get('dimensions') !== []),

                        Select::make('subDimensions')
                            ->hidden(fn(callable $get) => $get('dimensions') === [])
                            ->relationship('subDimensions', 'name')
                            ->options(fn(callable $get) => SubDimension::whereIn('dimension_id', $get('dimensions'))
                                ->orderBy('dimension_id')
                                ->get()
                                ->pluck('name', 'id')->toArray()
                            )
                            ->createOptionForm([
                                Select::make('dimension_id')
                                    ->relationship('dimension', 'name'),
                                TextInput::make('name'),
                                Textarea::make('notes'),
                            ])
                            ->multiple()
                            ->suffixAction(self::makeDiscussionPointAction()),

                    ]),


                Section::make('Description')
                    ->schema([
                        Textarea::make('definition')
                            ->inlineLabel()
                            ->helperText('brief description of the metric and what it measures')
                            ->suffixAction(self::makeDiscussionPointAction()),

                        Textarea::make('concept')
                            ->inlineLabel()
                            ->helperText('Description of the metric\'s relevance to assessing ag/food system performance')
                            ->suffixAction(self::makeDiscussionPointAction()),

                    ]),

                Section::make('Properties')
                    ->schema(function () {
                        $props = Property::where('default_type', '=', Metric::class)->get();

                        return $props->map(function ($property) {
                            if ($property->free_text) {
                                return Textarea::make('property_' . $property->id)
                                    ->label($property->name)
                                    ->inlineLabel()
                                    ->helperText($property->definition)
                                    ->suffixAction(self::makeDiscussionPointAction());

                            }

                            $component = Select::make('property_' . $property->id)
                                ->label($property->name)
                                ->inlineLabel()
                                ->multiple($property->select_multiple)
                                ->options(fn() => PropertyOption::where('property_id', '=', $property->id)
                                    ->pluck('name', 'id')->toArray())
                                ->suffixAction(self::makeDiscussionPointAction());

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
                                        return (string) PropertyOption::create($data)->id;
                                    });
                            }

                            return $component;

                        })->toArray();
                    })

            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                BadgeableColumn::make('title')
                    ->badges(function ($record): array {
                        return $record->topics->map(function ($topic) {
                            return Badge::make($topic->id)
                                ->label($topic->name)
                                ->color('success');
                        })->toArray();

                    }),
                Tables\Columns\TextColumn::make('developer.name'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make(),]);
    }

    public
    static function getRelations(): array
    {
        return [

            RelationGroup::make('Topics', [
                TopicsRelationManager::class,
                DimensionsRelationManager::class,
                SubDimensionsRelationManager::class,
            ]),

            RelationGroup::make('Scales', [
                ScaleDecisionRelationManager::class,
                ScaleMeasurementRelationManager::class,
                ScaleReportingRelationManager::class,
            ]),

            RelationGroup::make('Users', [
                CollectorsRelationManager::class,
                DecisionMakerRelationManager::class,
                ImpactedByRelationManager::class,
            ]),


            RelationGroup::make('Tools and Frameworks', [
                ToolsRelationManager::class,
                FrameworksRelationManager::class,
                UnitsRelationManager::class,
            ]),

            ComplimentaryMetricsRelationManager::class,

            RelationGroup::make('Systems and Geographies', [
                FarmingSystemsRelationManager::class,
                GeographiesRelationManager::class,
            ]),


        ];
    }

    public
    static function getPages(): array
    {
        return [
            'index' => Pages\ListMetrics::route('/'),
            'create' => Pages\CreateMetric::route('/create'),
            'view' => Pages\ViewMetric::route('/{record}'),
            'edit' => Pages\EditMetric::route('/{record}/edit'),
        ];
    }
}
