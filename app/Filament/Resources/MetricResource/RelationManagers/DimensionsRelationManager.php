<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\DimensionResource;
use App\Filament\Templates\MetricLinkRelationManager;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;

class DimensionsRelationManager extends MetricLinkRelationManager
{
    protected static string $relationship = 'dimensions';
    protected static ?string $inverseRelationship = 'metrics';
    protected static ?string $title = 'Dimensions';

    // override default view
    protected static string $view = 'filament.resources.relation-manager';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return DimensionResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('topic.name'),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Textarea::make('notes'),
                    ])
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
