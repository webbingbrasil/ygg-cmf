<?php

namespace Ygg\Resource\Screen\Actions;

use Ygg\Actions\Link as BaseLink;

class Link extends BaseLink
{
    /**
     * @param string $view
     *
     * @return self
     */
    public static function view(string $view): self
    {
        $link = new static();
        $link->name('Locale');
        $link->view = $view;

        return $link;
    }
}
