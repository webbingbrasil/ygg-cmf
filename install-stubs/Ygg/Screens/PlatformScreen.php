<?php

namespace App\Ygg\Screens;

use Ygg\Actions\Action;
use Ygg\Actions\Link;
use Ygg\Platform\Dashboard;
use Ygg\Screen\Layout;
use Ygg\Screen\Screen;

class PlatformScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Dashboard';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Welcome';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'status' => Dashboard::checkUpdate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function actions(): array
    {
        return [
            Link::make('Website')
                ->href('http://ygg.webbingbrasil.com.br')
                ->icon('icon-globe-alt'),

            Link::make('Documentation')
                ->href('http://ygg.webbingbrasil.com.br/en/docs')
                ->icon('icon-docs'),

            Link::make('GitHub')
                ->href('https://github.com/webbingbrasil/ygg-cmf')
                ->icon('icon-social-github'),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::view('platform::partials.update'),
            Layout::view('platform::partials.welcome'),
        ];
    }
}
