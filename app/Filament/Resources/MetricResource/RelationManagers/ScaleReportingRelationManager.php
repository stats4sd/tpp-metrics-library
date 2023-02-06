<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ScaleReportingRelationManager extends RelationManager
{
    protected static string $relationship = 'scaleReporting';
    protected static ?string $inverseRelationship = 'metricReporting';

    protected static ?string $recordTitleAttribute = 'name';

    public function getTableDescription(): string
    {
        return 'The scale(s) at which this metric is reported at.';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \App\Filament\Form\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                \App\Filament\Form\Placeholder::make('Notes')
                    ->content('Add any extra information about how/why this metric is reported at this scale'),
                \App\Filament\Form\Textarea::make('notes'),
                \App\Filament\Form\Checkbox::make('commonly_used')
                    ->label('Is the metric commonly reported at this scale?'),
                \App\Filament\Form\Hidden::make('type')
                    ->default('reporting')
            ]);
    }

    public static function table(Table $table): Table
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
                Tables\Actions\CreateAction::make()
                    ->label('Create New Scale'),
                Tables\Actions\AttachAction::make('Attach Existing')
                    ->preloadRecordSelect()
                    ->recordSelect(fn(\App\Filament\Form\Select $select) => $select->multiple())
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        \App\Filament\Form\Placeholder::make('Notes')
                            ->content('Add any extra information about how/why this metric can be reported at this scale'),
                        \App\Filament\Form\Textarea::make('notes'),
                        \App\Filament\Form\Checkbox::make('commonly_used')
                            ->label('Is the metric commonly reported on at this scale?'),
                        \App\Filament\Form\Hidden::make('type')
                            ->default('reporting'),
                    ]),
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
