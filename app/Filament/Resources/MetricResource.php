<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetricResource\Pages;
use App\Filament\Resources\MetricResource\RelationManagers\DimensionsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\MetricFrameworksRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\MetricMethodsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\MetricPropertiesRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\MetricScalesRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\MetricToolsRelationManager;
use App\Filament\Resources\MetricResource\RelationManagers\MetricUsersRelationManager;
use App\Models\Metric;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class MetricResource extends Resource
{
    protected static ?string $model = Metric::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Metric Information')
                    ->schema([
                        TextInput::make('title'),
                        Repeater::make('altNames')
                            ->relationship()
                            ->schema([
                                TextInput::make('name')->inlineLabel(),
                                Textarea::make('notes')->inlineLabel(),
                            ])
                            ->collapsed(),
                        Select::make('developer')
                    ]),
                // topics, dimensions, sub-dimensions = new tab
                // scales, tools, frameworks = new tab

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
            MetricPropertiesRelationManager::class,
            RelationGroup::make('Tools, Methods + Frameworks', [
                MetricFrameworksRelationManager::class,
                MetricToolsRelationManager::class,
                MetricMethodsRelationManager::class,
            ]),
            DimensionsRelationManager::class,
            MetricScalesRelationManager::class,
            MetricUsersRelationManager::class,
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
