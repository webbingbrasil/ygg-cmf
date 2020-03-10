<?php


namespace Ygg\Actions;

use Ygg\Screen\Repository;

/**
 * Class State.
 *
 * @method State name(string $name = null)
 * @method State modal(string $modalName = null)
 * @method State icon(string $icon = null)
 * @method State color(string $color = null)
 * @method State class(string $classes = null)
 * @method State method(string $methodName = null)
 * @method Button parameters(array|object $name)
 */
class State extends Action
{
    /**
     * @var string
     */
    protected $view = 'platform::actions.state';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'class'  => 'btn btn-link',
        'type'        => 'submit',
        'source' => null,
        'icon'   => 'icon-circle',
        'color'   => 'black',
        'method'      => null,
        'action'      => null,
        'parameters'  => [],
        'states'   => [],
    ];

    /**
     * Create instance of the button.
     *
     * @param string $name
     *
     * @return DropDown
     */
    public static function make(string $name = ''): ActionInterface
    {
        return self::buildInstance($name, function () use ($name) {
            $url = url()->current();
            $query = http_build_query($this->get('parameters'));

            $action = "{$url}/{$this->get('method')}?{$query}";
            $this->set('action', $action);
        });
    }

    /**
     * @param ActionInterface[] $list
     *
     * @return DropDown
     */
    public function states(array $states): self
    {
        return $this->set('states', $states);
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
