<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Metric;
use App\Models\Property;
use Filament\Forms\Form;
use App\Models\Dimension;
use Filament\Tables\Table;
use App\Models\PropertyOption;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\MetricResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use App\Filament\Resources\Traits\HasDiscussionPoints;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Table\Actions\DeduplicateRecordsAction;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use App\Filament\Resources\MetricResource\RelationManagers\ToolsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\UnitsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ReferenceRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\CollectorsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\DimensionsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\FrameworksRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ImpactedByRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\DataSourcesRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\GeographiesRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ChildMetricsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\DecisionMakerRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ParentMetricsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ScaleDecisionRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ScaleReportingRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ScaleMeasurementRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\CollectionMethodsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ComputationGuidanceRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\ComplimentaryMetricsRelationManager;

class MetricResource extends Resource
{
    use HasDiscussionPoints;

    protected static ?string $model = Metric::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = '';
    protected static ?int $navigationSort = 1;

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

                                Placeholder::make('info1'),

                                /** 0.b Alt Names */
                                TableRepeater::make('altNames')
                                    ->label('0.b. Alternative Names')
                                    ->defaultItems(0)
                                    ->hiddenLabel()
                                    ->hint('Any other names the metric is known by')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('name')
                                            ->helperText('The alternate name'),
                                        TextInput::make('notes')
                                            ->helperText('E.g. Where is this name used? Who uses it? Is it a common name, or only occasionally used?'),
                                    ])
                                    ->createItemButtonLabel('Add new name')
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? '(new name)'),


                                Placeholder::make('info2')
                                    ->content(new HtmlString('<hr/>')),

                                /** 0.i Developer */
                                Select::make('developer_id')
                                    ->inlineLabel()
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
                                    ->hint('A brief description of the metric and what it measures'),

                                Textarea::make('concept')
                                    ->label('1.b. Concept')
                                    ->inlineLabel()
                                    ->hint('A description of the metric\'s relevance to assessing ag/food system performance'),

                                Placeholder::make('info3')
                                    ->content(new HtmlString('<hr/>')),

                                Toggle::make('unreviewed_import')
                                    ->label('Mark this imported record as reviewed')
                                    ->visible(function (Model $record): bool {
                                        $visible = $record->unreviewed_import == 1;
                                        return $visible;
                                    })
                                    ->offColor('success')
                                    ->onColor('danger')
                                    ->offIcon('heroicon-s-check')
                                    ->onIcon('heroicon-s-exclamation-circle'),

                            ]),

                        Tab::make('Properties')
                            ->hiddenOn(Pages\CreateMetric::class)
                            ->schema(function () {
                                $props = Property::where('default_type', '=', Metric::class)->get();

                                return $props->map(function ($property) {

                                    // a single property can include 1 or 2 fields (a select and a free-text).
                                    $components = [];


                                    if ($property->select_options) {

                                        $component = Select::make('property_' . $property->id)
                                            ->label($property->code . ' - ' . $property->name)
                                            ->inlineLabel()
                                            ->hint($property->definition)
                                            ->multiple($property->select_multiple)
                                            ->options(fn () => PropertyOption::where('property_id', '=', $property->id)
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
                BadgeableColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('developer.name'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated'),
                IconColumn::make('unreviewed_import')
                    ->options(['heroicon-o-exclamation-circle' => fn ($state): bool => (bool)$state])
                    ->color('danger')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('unreviewed_import')
                    ->query(fn (Builder $query): Builder => $query->where('unreviewed_import', true))
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

    public
    static function getRelations(): array
    {
        return [

            RelationGroup::make('Dimensions', [
                DimensionsRelationManager::class,
            ]),

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


            RelationGroup::make('Tools, Methods, Frameworks', [
                ToolsRelationManager::class,
                FrameworksRelationManager::class,
                CollectionMethodsRelationManager::class,
                UnitsRelationManager::class,
            ]),

            RelationGroup::make('Related Metrics', [
                ParentMetricsRelationManager::class,
                ComplimentaryMetricsRelationManager::class,
                ChildMetricsRelationManager::class,
            ]),

            RelationGroup::make('Systems and Geographies', [
                GeographiesRelationManager::class,
            ]),

            RelationGroup::make('References', [
                DataSourcesRelationManager::class,
                ComputationGuidanceRelationManager::class,
                ReferenceRelationManager::class,
            ]),

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMetrics::route('/'),
            'create' => Pages\CreateMetric::route('/create'),
            'edit' => Pages\EditMetric::route('/{record}/edit'),
            'view' => Pages\ViewMetric::route('/{record}'),
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
