<?php

namespace App\Ygg\Screens\Examples;

use Ygg\Actions\Action;
use Ygg\Screen\Fields\Input;
use Ygg\Screen\Layout;
use Ygg\Screen\Screen;

class ExampleLayoutsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Overview layouts';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Components for laying out your project';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
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
            Layout::view('platform::dummy.block'),

            Layout::tabs([
                'Example Tab 1' => Layout::view('platform::dummy.block'),
                'Example Tab 2' => Layout::view('platform::dummy.block'),
                'Example Tab 3' => Layout::view('platform::dummy.block'),
            ]),

            Layout::collapse([
                Input::make('collapse-1')->title('First name'),
                Input::make('collapse-2')->title('Last name'),
                Input::make('collapse-3')->title('Username'),
            ])->label('Click for me!'),

            Layout::columns([
                Layout::view('platform::dummy.block'),
                Layout::view('platform::dummy.block'),
                Layout::view('platform::dummy.block'),
            ]),

            Layout::accordion([
                'Collapsible Group Item #1' => Layout::view('platform::dummy.block'),
                'Collapsible Group Item #2' => Layout::view('platform::dummy.block'),
                'Collapsible Group Item #3' => Layout::view('platform::dummy.block'),
            ]),

        ];
    }
}
