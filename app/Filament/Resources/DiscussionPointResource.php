<?php

namespace App\Filament\Resources;

use App\Filament\Form\Components\Select;
use App\Filament\Form\Components\Textarea;
use App\Filament\Resources\DiscussionPointResource\Pages;
use App\Filament\Resources\DiscussionPointResource\RelationManagers;
use App\Models\DiscussionPoint;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class DiscussionPointResource extends Resource
{
    protected static ?string $model = DiscussionPoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('subject_type_label')
                    ->label('Entity Type'),

                Select::make('subject')
                    ->relationship('subject', fn($record): string => $record->subject->name ? 'name' : ($record->subject->title ? 'title' : ''))
                    ->label('Entity Name'),
                TextInput::make('property')
                    ->label('Property for Discussion'),
                Select::make('property_value')
                    ->relationship('property_value', 'name')
                    ->label('Current value of property'),
                Select::make('user')
                    ->relationship('user', 'name')
                    ->label('Added  by'),
                Textarea::make('notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject_type_label')
                    ->description(fn($record): string => $record->subject->title),
                TextColumn::make('property')
                    ->description(fn($record): string => $record->property_value?->name ?? ''),
                TextColumn::make('user.name'),
                Tables\Columns\IconColumn::make('resolved_at')
                    ->options([
                        'heroicon-o-check-circle' => fn($state): bool => (bool)$state,
                    ])
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_unresolved')
                    ->query(fn(Builder $query): Builder => $query->whereNull('resolved_at')),
            ])
            ->actions([
                Tables\Actions\Action::make('Resolve')
                    ->label(fn(DiscussionPoint $record): string => $record->resolved_at ? 'Mark as unresolved' : 'Mark as resolved')
                    ->action(function (DiscussionPoint $record) {

                        if ($record->resolved_at) {
                            $record->update([
                                'resolved_at' => null,
                            ]);
                        } else {
                            $record->update([
                                'resolved_at' => Carbon::now(),
                            ]);
                        }
                    }),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscussionPoints::route('/'),
            'create' => Pages\CreateDiscussionPoint::route('/create'),
        ];
    }
}
