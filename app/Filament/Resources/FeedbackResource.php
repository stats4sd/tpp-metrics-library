<?php

namespace App\Filament\Resources;

use App\Filament\Form\Components\Textarea;
use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Models\Feedback;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->columns(
//
//                $followup = $feedback->feedbackFollowups()
//                    ->map(function($followup) {
//                        return Tables\Columns\Layout\Panel::make([
//                            Tables\Columns\TextColumn::make('feedbackFollowup')
//                            ->description(fn(FeedbackFollowup $followup): string => $followup->comments),
//                        ]);
//                    });

                [
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\BadgeColumn::make('type')
                            ->colors([
                                'danger' => 'bug',
                                'primary' => 'feature_request',
                                'success' => 'comment',
                            ]),
                        Tables\Columns\TextColumn::make('user.name'),
                        Tables\Columns\TextColumn::make('url'),
                        Tables\Columns\IconColumn::make('resolved_at')
                            ->options([
                                'heroicon-o-check-circle' => fn($state): bool => (bool)$state,
                            ])
                            ->color('success'),

                    ]),
                    
                    Tables\Columns\Layout\Panel::make([
                        Tables\Columns\TextColumn::make('comments'),
                        Tables\Columns\ViewColumn::make('followup')
                            ->view('filament.tables.columns.feedback-followup')
                    ])->collapsible()->collapsed(true),

                ])
            ->actions([
                Tables\Actions\Action::make('Resolve')
                    ->label(fn(Feedback $record): string => $record->resolved_at ? 'Mark as unresolved' : 'Mark as resolved')
                    ->action(function (Feedback $record) {

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
                Tables\Actions\Action::make('Add Followup')
                    ->action(function (Feedback $record, array $data) {
                        $data['user_id'] = Auth::id();
                        if ($data['resolved']) {
                            $record->update([
                                'resolver_id' => Auth::id(),
                                'resolved_at' => Carbon::now(),
                            ]);
                        }

                        unset($data['resolved']);

                        $record->feedbackFollowups()
                            ->create($data);

                    })
                    ->form([
                        Textarea::make('comments')->required(),
                        Checkbox::make('resolved')->label('Is this feedback now resolved?'),
                    ]),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
