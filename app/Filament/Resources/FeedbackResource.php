<?php

namespace App\Filament\Resources;

use App\Filament\Form\Components\Textarea;
use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Models\Feedback;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Radio::make('type')
                    ->options([
                        'bug' => 'Bug',
                        'feature_request' => 'Feature Request',
                        'comment' => 'General Comment',
                    ])
                    ->required()
                    ->default('comment'),
                Textarea::make('comments')
                    ->label('Add information about the feedback.')
                    ->required()
                    ->hint('Please be as detailed as possible.'),
                TextInput::make('url')
                    ->label('If the feedback relates to a specific page in the system, please add the url'),
                SpatieMediaLibraryFileUpload::make('attachments')
                    ->multiple(),
                Hidden::make('user_id')
                    ->default(Auth::id())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('url'),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\TextColumn::make('comments'),
                ])->collapsible(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFeedback::route('/'),
        ];
    }
}
