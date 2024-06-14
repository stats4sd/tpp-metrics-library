<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToolResource\Pages;
use App\Filament\Resources\ToolResource\RelationManagers;
use App\Models\Tool;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ToolResource extends Resource
{
    protected static ?string $model = Tool::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'TOOLS, METHODS + FRAMEWORKS';
    protected static ?int $navigationSort = 41;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name')->required(),
                        Textarea::make('definition'),
                        Textarea::make('notes'),
                    ])
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Information')
                    ->schema([
                        TextEntry::make('web_ref')->label('Website')
                            ->inlineLabel()
                            ->url(fn($record) => $record->web_ref),
                        TextEntry::make('author')->inlineLabel(),

                        Fieldset::make('Publication')
                            ->schema([

                                TextEntry::make('year_published'),
                                TextEntry::make('year_updated')
                                    ->visible(fn($record) => $record->updated),
                                TextEntry::make('updated_ref')
                                    ->visible(fn($record) => $record->updated),
                            ]),
                        Fieldset::make('Real World Application')
                            ->schema([
                                IconEntry::make('wider_use')
                                    ->label('Has this tool seen wide use?')
                                    ->boolean(),
                                TextEntry::make('wider_use_evidence')
                                    ->label('Evidence of wider use')
                                    ->visible(fn($record) => $record->wider_use)
                                    ->url(fn($record) => $record->wider_use_evidence)
                                    ->columnSpan(2),
                                TextEntry::make('wider_use_notes')
                                    ->label('Notes on wider use')
                                    ->columnSpan(2),
                                IconEntry::make('adapted')
                                    ->label('Has this tool been adapted to different use cases or contexts?')
                                    ->boolean(),
                                TextEntry::make('adapted_ref')
                                    ->label('Evidence of adaptation')
                                    ->visible(fn($record) => $record->adapted)
                                    ->url(fn($record) => $record->adapted_ref)
                                    ->columnSpan(2),
                                TextEntry::make('adapted_notes')
                                    ->label('Notes on adaptation')
                                    ->columnSpan(2),

                            ])->columns(5),
                        Fieldset::make('Development Process')
                            ->schema([
                                TextEntry::make('stakeholder_involved')
                                ->label(''),
                            ]),
                        TextEntry::make('conceptual_framing'),
                        TextEntry::make('framing_definition'),
                        TextEntry::make('framing_indicator_link'),
                        TextEntry::make('indicator_convenience'),
                        TextEntry::make('sustainability_view'),
                        TextEntry::make('tool_orientiation'),
                        TextEntry::make('localisable'),
                        TextEntry::make('system_type'),
                        TextEntry::make('verifiable'),

                        TextEntry::make('complexity'),
                        TextEntry::make('access'),
                        TextEntry::make('paid_access'),
                        TextEntry::make('online_platform'),
                        TextEntry::make('guide_assess'),
                        TextEntry::make('guide_analysis'),
                        TextEntry::make('guide_interpret'),
                        TextEntry::make('guide_data_gov'),
                        TextEntry::make('visualise_result'),
                        TextEntry::make('visualise_type'),
                        TextEntry::make('assessment_results'),
                        TextEntry::make('metric_no'),
                        TextEntry::make('collection_time'),
                        TextEntry::make('interaction'),
                        TextEntry::make('interaction_expl'),
                        TextEntry::make('aggregation'),
                        TextEntry::make('weighting'),
                        TextEntry::make('weighting_preference'),
                        TextEntry::make('comments'),
                        TextEntry::make('once_multi'),
                        TextEntry::make('metric_eval'),
                    ])
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('acronym')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('metrics_count')->counts('metrics')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->recordUrl(fn($record) => static::getUrl('view', [$record]));
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MetricsRelationManager::class,
            RelationManagers\FrameworksRelationManager::class,
            RelationManagers\ReferencesRelationManager::class,
            RelationManagers\DimensionsRelationManager::class,
            RelationManagers\MetricUsersRelationManager::class,
            RelationManagers\ThemesRelationManager::class,
            RelationManagers\ScalesRelationManager::class,


        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTools::route('/'),
            'create' => Pages\CreateTool::route('/create'),
            'view' => Pages\ViewTool::route('/{record}'),
            'edit' => Pages\EditTool::route('/{record}/edit'),
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
