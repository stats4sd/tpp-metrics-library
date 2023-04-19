<?php

namespace App\Filament\Resources\Traits;

use App\Models\Flag;
use App\Models\DiscussionPoint;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Filament\Form\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;

trait HasDiscussionPoints
{

    public static function makeDiscussionPointAction(): \Closure
    {
        return fn(): Action => Action::make('discussion_point')
            ->label('Discussion Point')
            ->tooltip(fn(): string => "Add discussion point about this")
            ->icon('heroicon-s-external-link')
            ->form(function (Action $action): array {

                $modelType = $action->getComponent()->getModel();
                $model = $action->getComponent()->getRecord();
                $property = $action->getComponent()->getLabel();

                $modelTitle = $model->title ?? $model?->name;

                $title = new HtmlString(
                    "<ul>
                        <li>Item Type: <b>{$modelType}</b></li>
                        <li>Item name: <b>{$modelTitle}</b></li>
                        <li>Property: <b>{$property}</b></li>

                                    ");
                return [
                    Placeholder::make('title-dp')
                        ->label('Add a discussion point for:')
                        ->content($title),
                    TextArea::make('notes')
                        ->inlineLabel()
                        ->label('Add any notes for discussion'),
                    Select::make('flag_id')
                        ->inlineLabel()
                        ->label('Add a flag to the discussion point')
                        ->placeholder('Select a flag')
                        ->options(Flag::all()->pluck(value:'name',key:'id')->toArray()),
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
