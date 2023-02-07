<?php

namespace App\Filament\Resources;

use App\Filament\Form\Components\CheckboxList;
use App\Filament\Form\Components\Repeater;
use App\Filament\Form\Components\Select;
use App\Filament\Form\Components\Textarea;
use App\Filament\Resources\MetricResource\Pages;
use App\Filament\Resources\MetricResource\RelationManagers\CollectorsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ComplimentaryMetricsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ComputationGuidanceRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\DataSourcesRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\DecisionMakerRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\FarmingSystemsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\FrameworksRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\GeographiesRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ImpactedByRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ParentChildMetricsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ReferenceRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ScaleDecisionRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ScaleMeasurementRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ScaleReportingRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ToolsRelationManager;
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
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
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

                Tabs::make('metrics-tabs')
                    ->columnSpanFull()
                    ->schema([
                        Tab::make('Core Info')
                            ->schema([

                                /** 0.a Title */
                                TextInput::make('title')
                                    ->label('0.a. Title')
                                    ->required()
                                    ->inlineLabel()
                                    ->hint('The identifying name for the metric')
                                    ->suffixAction(self::makeDiscussionPointAction()),

                                Placeholder::make('-'),

                                /** 0.b Alt Names */
                                Repeater::make('altNames')
                                    ->defaultItems(0)
                                    ->collapsed()
                                    ->label('0.b. Alternative Names')
                                    ->hint('Any other names the metric is known by')
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
                                    ->label('0.i. Developer')
                                    ->hint('Who is responsible for having developed this metric?')
                                    ->relationship('developer', 'name')
                                    ->createOptionForm([
                                        TextInput::make('name'),
                                        Textarea::make('notes')
                                    ])
                                    ->suffixAction(self::makeDiscussionPointAction()),

                                Textarea::make('definition')
                                    ->label('1.a. Definition')
                                    ->inlineLabel()
                                    ->hint('A brief description of the metric and what it measures')
                                    ->suffixAction(self::makeDiscussionPointAction()),

                                Textarea::make('concept')
                                    ->label('1.b. Concept')
                                    ->inlineLabel()
                                    ->hint('A description of the metric\'s relevance to assessing ag/food system performance')
                                    ->suffixAction(self::makeDiscussionPointAction()),

                            ]),

                        Tab::make('Topics and Dimensions')
                            ->schema([

                                /** 0.c Topics */
                                CheckboxList::make('topics')
                                    ->label('0.c. Topics')
                                    ->relationship('topics', 'name')
                                    ->columns(2)
                                    ->options(Topic::orderBy('id')->get()->pluck('name', 'id')->toArray())
                                    ->reactive()
                                    ->suffix('Add discussion point')
                                    ->suffixAction(self::makeDiscussionPointAction()),

                                /** 0.d Dimensions */
                                Placeholder::make('Dimensions')
                                    ->label('0.d. Dimensions')
                                    ->content('Select one or more Topics to see available dimensions')
                                    ->hidden(fn(callable $get) => $get('topics') !== []),

                                CheckboxList::make('dimensions')
                                    ->label('0.d. Dimensions')
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
                                    ->label('0.e. Sub-dimensions')
                                    ->content('Select one or more Dimensions before selecting sub-dimensions')
                                    ->hidden(fn(callable $get) => $get('dimensions') !== []),

                                Select::make('subDimensions')
                                    ->label('0.e. Sub-dimensions')
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
                                    ->createOptionAction(fn(Action $action): Action => $action->tooltip('Create new'))
                                    ->suffixAction(self::makeDiscussionPointAction()),
                            ]),
                        Tab::make('Properties')
                            ->schema(function () {
                                $props = Property::where('default_type', '=', Metric::class)->get();

                                return $props->map(function ($property) {
                                    if ($property->free_text) {
                                        return Textarea::make('property_' . $property->id)
                                            ->label($property->code . ' - ' . $property->name)
                                            ->inlineLabel()
                                            ->helperText($property->definition)
                                            ->suffixAction(self::makeDiscussionPointAction());

                                    }

                                    $component = Select::make('property_' . $property->id)
                                        ->label($property->code . ' - ' . $property->name)
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
                                                return (string)PropertyOption::create($data)->id;
                                            });
                                    }

                                    return $component;

                                })->toArray();
                            })
                    ])
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public
    static function getRelations(): array
    {
        return [

//            RelationGroup::make('Topics', [
//                TopicsRelationManager::class,
//                DimensionsRelationManager::class,
//                SubDimensionsRelationManager::class,
//            ]),

            RelationGroup::make('Scales', [
                ScaleDecisionRelationManager::class,
                ScaleMeasurementRelationManager::class,
                ScaleReportingRelationManager::class,
            ]),

            RelationGroup::make('Users + Use Cases', [
                CollectorsRelationManager::class,
                DecisionMakerRelationManager::class,
                ImpactedByRelationManager::class,
            ]),


            RelationGroup::make('Tools and Frameworks', [
                ToolsRelationManager::class,
                FrameworksRelationManager::class,
                UnitsRelationManager::class,
            ]),

            RelationGroup::make('Related Metrics', [
                ComplimentaryMetricsRelationManager::class,
                ParentChildMetricsRelationManager::class,
            ]),

            RelationGroup::make('Systems and Geographies', [
                FarmingSystemsRelationManager::class,
                GeographiesRelationManager::class,
            ]),

            RelationGroup::make('References', [
                DataSourcesRelationManager::class,
                ComputationGuidanceRelationManager::class,
                ReferenceRelationManager::class,
            ]),

        ];
    }

    public
    static function getPages(): array
    {
        return [
            'index' => Pages\ListMetrics::route('/'),
            'create' => Pages\CreateMetric::route('/create'),
            'edit' => Pages\EditMetric::route('/{record}/edit'),
        ];
    }
}
