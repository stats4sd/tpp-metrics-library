<?php

namespace App\Filament\Table\Actions;


use App\Models\DiscussionPoint;
use App\Models\Flag;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class AddDiscussionPointAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'add_discussion_point';
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->label('Discussion Point');
        $this->tooltip('Add discussion point about this item');
        $this->icon('heroicon-m-arrow-top-right-on-square');

        $this->form(function (AddDiscussionPointAction $action): array {

            $model = $action->getLivewire()->getOwnerRecord();
            $modelType = get_class($model);
            $property = $action->getTable()->getHeading();
            $propertyModelType = $action->getTable()->getModel();
            $propertyValue = ($action->getRecord()->title ?? $action->getRecord()->name) ?? '';

            $modelTitle = $model->title ?? $model?->name;

            $title = new HtmlString(
                "<ul>
                    <li>Item Type: <b>{$modelType}</b></li>
                    <li>Item name: <b>{$modelTitle}</b></li>
                    <li>Property: <b>{$property}</b></li>
                    <li>Property Type: <b>{$propertyModelType}</b></li>
                    <li>Property Item: <b>{$propertyValue}</b></li>

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
                Hidden::make('property_value_id')
                    ->default($action->getRecord()->id),
                Hidden::make('property_value_type')
                    ->default($propertyModelType),
                Hidden::make('user_id')
                    ->default(Auth::id()),

            ];
        });

        $this->action(function (array $data, Action $action): void {
            DiscussionPoint::create($data);
        });
    }
}
