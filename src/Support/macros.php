<?php

use Ygg\Platform\Dashboard;
use Ygg\Resource\Models\Resource;
use Ygg\Actions\Link;
use Ygg\Screen\TD;

TD::macro('linkResource', function (string $text = '') {
    $this->render(static function (Resource $resource) use ($text) {
        return (string) Link::make($text)
            ->href(route('platform.resource.type.edit', [
                'type' => $resource->type,
                'resource' => $resource,
            ]));
    });

    return $this;
});

TD::macro('column', function (string $column = null, bool $localized = false) {
    if ($column !== null) {
        $this->column = $column;
    }
    if ($localized && $column !== null) {
        $locale = '.'.app()->getLocale().'.';
        $this->column = preg_replace('/'.preg_quote('.', '/').'/', $locale, $column);
    }

    return $this;
});
