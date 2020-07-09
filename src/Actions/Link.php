<?php

namespace Ygg\Actions;

use Ygg\Screen\Repository;

/**
 * Class Link.
 *
 * @method Link name(string $name = null)
 * @method Link icon(string $icon = null)
 * @method Link class(string $classes = null)
 * @method Link parameters(array|object $name)
 * @method Link target(string $target = null)
 * @method Link title(string $title = null)
 * @method Link download($download = true)
 */
class Link extends Action
{
    /**
     * @var string
     */
    protected $view = 'platform::actions.link';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'class'       => 'btn btn-link',
        'icon'        => null,
        'source'        => null,
        'href'        => '#!',
        'turbolinks'  => true,
    ];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    public $inlineAttributes = [
        'autofocus',
        'disabled',
        'tabindex',
        'href',
        'target',
        'title',
        'download',
    ];

    /**
     * Create instance of the button.
     *
     * @param string $name
     *
     * @return self
     */
    public static function make(string $name = ''): ActionInterface
    {
        return self::buildInstance($name);
    }

    /**
     * Set the link.
     *
     * @param string $link
     *
     * @return $this
     */
    public function href(string $link = ''): self
    {
        $this->set('href', $link);

        return $this;
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $absolute
     *
     * @return $this
     */
    public function route(string $name, $parameters = [], $absolute = true): self
    {
        $route = '';
        if($this->isSee()) {
            $route = route($name, $parameters, $absolute);
        }

        return $this->href($route);
    }

    /**
     * @param Repository $repository
     *
     * @throws \Throwable
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function build(Repository $repository = null)
    {
        $this->set('source', $repository);

        return $this->render();
    }
}
