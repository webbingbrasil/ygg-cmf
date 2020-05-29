<?php

namespace App\Ygg\Screens\Examples;

use Ygg\Actions\Action;
use Ygg\Platform\Models\Role;
use Ygg\Screen\Field;
use Ygg\Screen\Fields\CheckBox;
use Ygg\Screen\Fields\Code;
use Ygg\Screen\Fields\Cropper;
use Ygg\Screen\Fields\DateRange;
use Ygg\Screen\Fields\DateTimer;
use Ygg\Screen\Fields\Input;
use Ygg\Screen\Fields\Map;
use Ygg\Screen\Fields\Matrix;
use Ygg\Screen\Fields\Quill;
use Ygg\Screen\Fields\Radio;
use Ygg\Screen\Fields\RadioButtons;
use Ygg\Screen\Fields\Relation;
use Ygg\Screen\Fields\Select;
use Ygg\Screen\Fields\SimpleMDE;
use Ygg\Screen\Fields\Switcher;
use Ygg\Screen\Fields\TextArea;
use Ygg\Screen\Fields\TinyMCE;
use Ygg\Screen\Fields\Upload;
use Ygg\Screen\Fields\UTM;
use Ygg\Screen\Layout;
use Ygg\Screen\Screen;

class ExampleFieldsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Form controls';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Examples for creating a wide variety of forms.';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'name' => 'Hello! We collected all the fields in one place',
            'place' => [
                'lat' => 37.181244855427394,
                'lng' => -3.6021993309259415,
            ],
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return array
     * @throws \Throwable
     *
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Field::group([

                    Input::make('name')
                        ->type('text')
                        ->max(255)
                        ->required()
                        ->title('Name Articles')
                        ->help('Article title')
                        ->popover('Tooltip - hint that user opens himself.'),

                    Input::make('title')
                        ->type('text')
                        ->max(255)
                        ->required()
                        ->title('Article Title')
                        ->help('SEO title')
                        ->popover('Tooltip - hint that user opens himself.'),

                ]),

                Field::group([

                    DateTimer::make('open')
                        ->title('Opening date')
                        ->help('The opening event will take place'),

                    Input::make('phone')
                        ->type('text')
                        ->mask('(999) 999-9999')
                        ->title('Phone')
                        ->help('Number Phone'),

                    CheckBox::make('free-checkbox')
                        ->sendTrueOrFalse()
                        ->title('Free checkbox')
                        ->placeholder('Event for free')
                        ->help('Event for free'),

                    Switcher::make('free-switch')
                        ->sendTrueOrFalse()
                        ->title('Free switch')
                        ->placeholder('Event for free')
                        ->help('Event for free'),
                ]),

                TextArea::make('description')
                    ->max(255)
                    ->rows(5)
                    ->required()
                    ->title('Short description'),

                Field::group([

                    DateTimer::make('allowInput')
                        ->title('DateTimer allowInput')
                        ->allowInput(),

                    DateTimer::make('enabledTime')
                        ->title('DateTimer enabledTime')
                        ->enableTime(),

                    DateTimer::make('format24hr')
                        ->title('DateTimer format24hr')
                        ->enableTime()
                        ->format24hr(),

                    DateTimer::make('custom')
                        ->title('DateTimer Custom')
                        ->noCalendar()
                        ->format('h:i K'),

                ]),

                Input::make('color')
                    ->type('color')
                    ->title('Select color'),

                DateRange::make('rangeDate')
                    ->title('Range date'),

                RadioButtons::make('radioButtons')
                    ->title('Status for radio buttons')
                    ->options([
                        1 => 'Enabled',
                        0 => 'Disabled',
                        3 => 'Pause',
                        4 => 'Work',
                    ]),

                TinyMCE::make('body')
                    ->required()
                    ->title('Name Articles')
                    ->help('Article title'),

                Map::make('place')
                    ->required()
                    ->title('Object on the map')
                    ->help('Enter the coordinates, or use the search'),

                Cropper::make('picture')
                    ->width(500)
                    ->height(300),

                UTM::make('link')
                    ->title('UTM link')
                    ->help('Generated link'),

                Select::make('robot.')
                    ->options([
                        'index' => 'Index',
                        'noindex' => 'No index',
                    ])
                    ->multiple()
                    ->title('Indexing')
                    ->help('Allow search bots to index'),

                SimpleMDE::make('body2')
                    ->title('Name Articles')
                    ->help('Article title'),

                Code::make('code')
                    ->title('Name Articles')
                    ->help('Article title'),

                Quill::make('body3')
                    ->title('Name Articles')
                    ->help('Article title'),

                Upload::make('files')
                    ->title('Upload files'),

                Relation::make('role')
                    ->fromModel(Role::class, 'name')
                    ->title('Select one role'),

                Radio::make('radio')
                    ->placeholder('Yes')
                    ->value(1)
                    ->title('Radio'),

                Radio::make('radio')
                    ->placeholder('No')
                    ->value(0),

                Matrix::make('matrix')
                    ->columns([
                        'Attribute',
                        'Value',
                        'Units',
                    ]),
            ]),
        ];
    }
}
