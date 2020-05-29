<?php


namespace Ygg\Screen\Layouts;


use Ygg\Screen\Form\Builder;
use Ygg\Screen\Repository;

abstract class Rows extends Base
{
    /**
     * @var string
     */
    protected $view = 'platform::layouts.row';

    /**
     * @var int
     */
    protected $with = 100;

    /**
     * Base constructor.
     *
     * @param Base[] $layouts
     */
    public function __construct(array $fields = [])
    {
        $this->layouts = $fields;
    }

    /**
     * @param Repository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|void
     * @throws \Throwable
     */
    public function build(Repository $repository)
    {
        if (! $this->hasPermission($this, $repository)) {
            return;
        }

        $this->query = $repository;
        $form = new Builder($this->fields(), $repository);

        return view($this->view, [
            'with' => $this->with,
            'form' => $form->build(),
        ]);
    }

    /**
     * @deprecated
     *
     * @param int $with
     *
     * @return $this
     */
    public function with(int $with = 100): self
    {
        $this->with = $with;

        return $this;
    }

    /**
     * @return array
     */
    abstract protected function fields(): array;
}
