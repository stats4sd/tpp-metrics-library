<?php

namespace App\Filament\Resources;

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
use App\Models\Metric;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationGroup;
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

                Card::make(
                    [

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
                Card::make([
                    CheckboxList::make('topics')
                        ->relationship('topics', 'name')
                        ->columns(2),
                ]),


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
