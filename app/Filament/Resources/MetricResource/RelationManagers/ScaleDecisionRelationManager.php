<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Forms\Components\Textarea;
use App\Filament\Resources\Traits\HasDiscussionPoints;
use App\Filament\Table\Actions\AddDiscussionPointAction;
use App\Models\Scale;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class ScaleDecisionRelationManager extends RelationManager
{
    use HasDiscussionPoints;

    protected static string $relationship = 'scaleDecision';
    protected static ?string $inverseRelationship = 'metricDecision';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = '0.f. Scales for Decision Making';

    public function getTableDescription(): string
    {
        return 'The scale(s) at which this metric can be used to make decisions.';
    }

    public function isTablePaginationEnabled(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Section::make('Scale')
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
                    ->label('Add any extra information about how/why this metric can be used at this scale to make decisions'),
                Checkbox::make('commonly_used')
                    ->label('Is the metric commonly used at this scale?'),
                Hidden::make('type')
                    ->default('decision making'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Scale'),
                Tables\Columns\IconColumn::make('commonly_used')
                    ->boolean()
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
                                ->label('Notes about this scale')
                                ->hint('This is not about how the scale relates to the current metric, but about the definition of the scale itself.'),
                        ])
                        ->createOptionAction(fn(Action $action) => $action->modalHeading('Create Scale Entry'))
                        ->createOptionUsing(fn(array $data) => Scale::create($data)->id)
                    )
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->autofocus(false),
                        Textarea::make('relation_notes')
                            ->label('Add any extra information about how/why this metric can be used at this scale to make decisions'),
                        Checkbox::make('commonly_used')
                            ->label('Is the metric commonly used at this scale?'),
                        Hidden::make('type')
                            ->default('decision making'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Link between scale and metric'),
                Tables\Actions\DetachAction::make(),
                AddDiscussionPointAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
