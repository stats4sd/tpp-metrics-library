<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\DimensionResource;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DimensionsRelationManager extends RelationManager
{
    protected static string $relationship = 'dimensions';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return DimensionResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                ->form(fn(AttachAction $action): array => [
                    $action->getRecordSelect(),
                    Forms\Components\Textarea::make('notes')->required(),
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
