<?php

namespace Ygg\Screens\Layouts;

use Illuminate\Contracts\View\Factory;
use Ygg\Screens\Form\Builder;
use Ygg\Screens\Repository;
use Throwable;

/**
 * Class Collapse.
 */
abstract class Collapse extends Base
{
    /**
     * @var string
     */
    protected $view = 'platform::layouts.collapse';

    /**
     * @var Repository
     */
    public $query;

    /**
     * @var string
     */
    private $label = 'Options';

    /**
     * Base constructor.
     *
     * @param Base[] $layouts
     */
    public function __construct(array $layouts = [])
    {
        $this->layouts = $layouts;
    }

    /**
     * @param Repository $repository
     *
     * @throws Throwable
     *
     * @return Factory|\Illuminate\View\View|void
     */
    public function build(Repository $repository)
    {
        if (! $this->hasPermission($this, $repository)) {
            return;
        }

        $this->query = $repository;
        $form = new Builder($this->fields(), $repository);

        return view($this->template, [
            'form'  => $form->generateForm(),
            'slug'  => $this->getSlug(),
            'label' => $this->label,
        ]);
    }

    /**
     * @param string $label
     *
     * @return Collapse
     */
    public function label(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return array
     */
    abstract protected function fields(): array;
}
