<?php

namespace Ygg\Actions;

use Ygg\Screens\Field;
use Ygg\Screens\Repository;
use Ygg\Support\Color;

abstract class Action extends Field implements ActionInterface
{
    /**
     * @deprecated
     */
    public const DEFAULT = 'default';

    /**
     * @deprecated
     */
    public const SUCCESS = 'success';

    /**
     * @deprecated
     */
    public const WARNING = 'warning';

    /**
     * @deprecated
     */
    public const DANGER = 'danger';

    /**
     * @deprecated
     */
    public const INFO = 'info';

    /**
     * @deprecated
     */
    public const PRIMARY = 'primary';

    /**
     * @deprecated
     */
    public const SECONDARY = 'secondary';

    /**
     * @deprecated
     */
    public const LIGHT = 'light';

    /**
     * @deprecated
     */
    public const DARK = 'dark';

    /**
     * @deprecated
     */
    public const LINK = 'link';

    /**
     * Override the form view.
     *
     * @var string
     */
    protected $typeForm = 'platform::partials.fields.clear';

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [
        'type',
        'autofocus',
        'disabled',
        'tabindex',
    ];

    /**
     * Create instance of the action.
     *
     * @param string $name
     *
     * @return ModalToggle
     */
    abstract static function make(string $name = ''): self;

    protected static function buildInstance(string $name, callable $beforeRender = null): self
    {
        /** @var self $instance */
        $instance = (new static())->name($name);
        if ($beforeRender) {
            $instance->addBeforeRender($beforeRender);
        }
        return $instance;
    }

    /**
     * Align button to the right.
     *
     * @return static
     */
    public function right(): self
    {
        $class = $this->get('class').' pull-right';

        $this->set('class', $class);

        return $this;
    }

    /**
     * @param Color|string $visual
     *
     * @throws \ReflectionException
     *
     * @return static
     */
    public function type($visual): self
    {
        $reflectionClass = new \ReflectionClass(Color::class);

        $colors = array_map(static function (string $color) {
            return 'btn-'.$color;
        }, $reflectionClass->getConstants());

        $class = str_replace($colors, '', (string) $this->get('class'));

        $this->set('class', $class.' btn-'.$visual);

        return $this;
    }

    /**
     * Set the button as block.
     *
     * @return static
     */
    public function block(): self
    {
        $class = $this->get('class').' pull-block';

        $this->set('class', $class);

        return $this;
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
        return $this->render();
    }

    /**
     * @param bool $status
     *
     * @return static
     */
    public function rawClick(bool $status = false): self
    {
        $this->set('turbolinks', $status);

        return $this;
    }

    /**
     * @return string
     */
    protected function getId(): ?string
    {
        return $this->get('id');
    }
}
