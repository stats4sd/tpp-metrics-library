<?php

namespace App\Filament\Resources\Traits;

use App\Models\DiscussionPoint;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

trait HasDiscussionPoints
{

    public static function makeDiscussionPointAction(): \Closure
    {
        return fn(): Action => Action::make('test')
            ->tooltip(fn(): string => "Add discussion point about this")
            ->icon('heroicon-s-external-link')
            ->form(function (Action $action): array {

                $modelType = $action->getComponent()->getModel();
                $model = $action->getComponent()->getRecord();
                $property = $action->getComponent()->getLabel();

                $modelTitle = $model->title ?? $model?->name;

                $title = new HtmlString(
                    "Add a discussion point for: <ul>
                                              <li>Item Type: <b>{$modelType}</b></li>
                                              <li>Item name: <b>{$modelTitle}</b></li>
                                              <li>Property: <b>{$property}</b></li>

                                    ");
                return [
                    Placeholder::make('title-dp')
                        ->label('Flag for discussion')
                        ->content($title),
                    TextArea::make('notes')
                        ->label('Add any notes for discussion'),
                    Hidden::make('subject_id')
                        ->default($model?->id),
                    Hidden::make('subject_type')
                        ->default($modelType),
                    Hidden::make('property')
                        ->default($property),
                    Hidden::make('user_id')
                        ->default(Auth::id()),

                ];

            })
            ->action(function (array $data, Action $action): void {
                DiscussionPoint::create($data);
            });
    }


}
