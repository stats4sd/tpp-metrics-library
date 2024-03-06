<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Forms\Components\Textarea;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

// ***********************
// NOTE - CURRENTLY UNUSED
// ***********************
// class DimensionsRelationManager extends RelationManager
class DimensionMetricsRelationManager extends RelationManager
{
    // protected static string $relationship = 'dimensions';
    protected static string $relationship = 'metrics';

    // protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $recordTitleAttribute = 'title';

    public function getTableHeading(): string
    {
        // return 'Dimensions for ' . $this->ownerRecord->title;
        return 'Metrics for ' . $this->ownerRecord->name;
    }

    // public function getTableDescription(): string
    // {

    //     $topicNames = $this->ownerRecord->topics
    //         ->map(fn ($topic) => $topic->name)
    //         ->join(', ');

    //     return "(Existing Topics: {$topicNames} )";
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Metric')
                    ->schema([
                        TextInput::make('title')
                            ->inlineLabel()
                            ->disabled(),
                    ]),
                Textarea::make('relation_notes')
                    // ->label('Add any extra information about why this metric is associated to the other'),
                    ->label('Add any extra information about why this metric is associated to the dimension'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                // ->recordSelect(
                //     fn (Select $select) => $select
                //         ->multiple()
                //         ->createOptionForm([
                //             Select::make('topic_id')
                //                 ->relationship('topic', 'name'),
                //             TextInput::make('title')
                //                 ->label('Enter the name of the new dimension'),
                //             Textarea::make('notes')
                //                 ->label('Add any notes about this dimension'),
                //         ])
                // )
                // ->form(fn (Tables\Actions\AttachAction $action): array => [
                //     $action->getRecordSelect(),
                //     Textarea::make('relation_notes')
                //         // ->hint('Add any extra information about how/why this metric is linked to this dimension.'),
                //         ->hint('Add any extra information about how/why this metric is linked to this metric.'),
                // ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
