<?php


namespace Ygg\Screens\Layouts;


use Ygg\Screens\Repository;

class View extends Base
{
    /**
     * @var array
     */
    private $data;

    /**
     * View constructor.
     * @param string $view
     * @param array $data
     */
    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }


    /**
     * @param Repository $repository
     * @return \Illuminate\Contracts\View\View|mixed|void
     */
    public function build(Repository $repository)
    {
        if(!$this->hasPermission($this, $repository)) {
            return;
        }

        $data = array_merge($this->data, $repository->toArray());
        return view()->make($this->view, $data);
    }
}
