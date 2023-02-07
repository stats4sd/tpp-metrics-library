<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class DimensionsRelationManager extends RelationManager
{
    protected static string $relationship = 'dimensions';

    protected static ?string $recordTitleAttribute = 'name';

    public function getTableHeading(): string
    {
        return 'Dimensions for ' . $this->ownerRecord->title;
    }

    public function getTableDescription(): string
    {

        $topicNames = $this->ownerRecord->topics
            ->map(fn($topic) => $topic->name)
            ->join(', ');

        return "(Existing Topics: {$topicNames} )";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
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
                ->preloadRecordSelect(),
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
