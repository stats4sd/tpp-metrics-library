<?php

namespace App\Filament\SharedRelationManagers;

use App\Filament\Resources\ToolResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class SharedToolsRelationManager extends RelationManager
{
    protected static string $relationship = 'tools';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultPaginationPageOption('all')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                IconColumn::make('unreviewed_import')
                    ->options(['heroicon-o-exclamation-circle' => fn($state): bool => (bool)$state])
                    ->color('danger')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                 Tables\Actions\ViewAction::make()->url(fn ($record, $livewire) => ToolResource::getUrl('view', ['record' => $record])),
            ])
            ->bulkActions([
            ]);
    }
}
