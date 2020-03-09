<?php

namespace Ygg\Platform\Http\Layouts;

use Ygg\Screen\Layouts\Table;
use Ygg\Screen\TD;

class NotificationTable extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'notifications';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('Message', __('Messages'))
                ->canHide()
                ->render(static function ($notification) {
                    return view('platform::partials.notification', [
                        'notification' => $notification,
                    ]);
                }),
        ];
    }

    /**
     * @return string
     */
    public function textNotFound(): string
    {
        return __('No notifications');
    }

    /**
     * @return string
     */
    public function iconNotFound(): string
    {
        return 'icon-bell';
    }

    /**
     * @return string
     */
    public function subNotFound(): string
    {
        return __('You currently have no notifications, but maybe they will appear later.');
    }
}
