<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Form\Components\Textarea;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use App\Models\Geography;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class GeographiesRelationManager extends RelationManager
{
    protected static string $relationship = 'geographies';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = "6.c. Geographies";

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Section::make('Geography')
                    ->schema([

                        TextInput::make('name')
                            ->inlineLabel()
                            ->disabled(),
                        Textarea::make('definition')
                            ->inlineLabel()
                            ->disabled(),
                    ]),
                Textarea::make('relation_notes')
                    ->inlineLabel()
                    ->label('Add any extra information about how/why this metric is linked to this farming system.')
                    ->hint('i.e. Is the metric particularly well-suited to this type of system? Or ill-suited? Is the metric often used when studying this type of system?'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Geography')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make('Attach')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(Select $select) => $select
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->inlineLabel()
                                ->maxLength(255),
                            Textarea::make('definition')
                                ->inlineLabel(),
                            Textarea::make('notes')
                                ->inlineLabel()
                                ->label('Notes about this geography')
                                ->hint('This is about the geographical entry itself, not about the link to the current metric.'),
                        ])
                        ->createOptionAction(fn(Action $action) => $action->modalHeading('Create Geography Entry'))
                        ->createOptionUsing(fn(array $data) => Geography::create($data)->id)
                    )
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->autofocus(false),
                        Textarea::make('relation_notes')
                            ->label('Add any extra information about how/why this metric is linked to this geography')
                            ->hint('i.e. Is the metric especially well suited for use in this geography? (Or ill-suited?) How widely used in this geography is the metric?'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit link between metric and farming system'),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
