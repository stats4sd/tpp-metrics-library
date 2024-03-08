<?php

namespace App\Filament\Resources\DimensionResource\RelationManagers;

use App\Models\Reference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReferencesRelationManager extends RelationManager
{
    protected static string $relationship = 'references';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make(fn(Reference $record) => $record->name)
                ->schema([
                    TextEntry::make('ref')
                        ->label('REFERENCE:')
                        ->state(fn(Reference $record): string => $record->journal . ' - Vol: ' . $record->volume . ' - Issue: ' . $record->issue . ' - Pages: ' . $record->pages . ' (' . $record->year . ');')->inlineLabel(),
                    TextEntry::make('authors')->label('AUTHORS:')->inlineLabel(),
                    TextEntry::make('publisher')
                        ->state(fn(Reference $record): string => $record->publisher . ' (' . $record->location . ')' . ' - ' . $record->doi)
                        ->label('PUBLISHER:')->inlineLabel(),
                    TextEntry::make('url')->label('URL:')->inlineLabel()
                    ->url(fn(Reference $record): string => $record->url)
                    ->openUrlInNewTab(),
                    TextEntry::make('language')->inlineLabel()->visible(fn(Reference $record): bool => (bool) $record->language),

                ]),
            Section::make('Abstract')
                ->schema([
                    TextEntry::make('abstract')->hiddenLabel(),
                ]),
            Section::make('Notes')
                ->schema([
                    TextEntry::make('notes')->hiddenLabel(),
                ]),
        ]);
    }

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
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->wrap(),
                Tables\Columns\IconColumn::make('has_url')
                    ->state(fn(Reference $record): bool => (bool)$record->url)
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->modalHeading(''),
            ])
            ->bulkActions([
            ]);
    }
}
