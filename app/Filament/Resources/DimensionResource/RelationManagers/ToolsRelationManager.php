<?php

namespace App\Filament\Resources\DimensionResource\RelationManagers;

use App\Models\Tool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class ToolsRelationManager extends RelationManager
{
    protected static string $relationship = 'tools';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make(fn(Tool $record) => $record->acronym . ' - ' . $record->name)
                ->schema([
                    TextEntry::make('author')->label('AUTHOR:')->inlineLabel(),
                    TextEntry::make('url')->label('URL:')->inlineLabel()
                        ->url(fn(Tool $record): ?string => $record->url)
                        ->openUrlInNewTab(),
                ]),
            Section::make('Wider Use')
                ->schema([
                    IconEntry::make('wider_use')->label('Tool is used widely?')
                        ->boolean()
                        ->inlineLabel(),
                    TextEntry::make('wider_use_evidence')->label('EVIDENCE:')->inlineLabel()
                        ->url(fn(Tool $record): string => $record->wider_use_evidence)
                        ->visible(fn(Tool $record): bool => (bool)$record->wider_use),
                    TextEntry::make('wider_use_notes')->label('NOTES:')->inlineLabel()
                        ->visible(fn(Tool $record): bool => (bool)$record->wider_use),
                ]),
            // TODO: Add lots of other things;
            // TODO: Potentially use this for the main Tools page as well...

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
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
            ]);
    }
}
