<?php


namespace Ygg\Screen\Layouts;


use Ygg\Screen\Repository;

abstract class Columns extends Base
{
    /**
     * @var string
     */
    protected $view = 'platform::layouts.columns';

    /**
     * @var array
     */
    protected $variables = [
        'wrapper' => true
    ];

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
     * @return mixed
     */
    public function build(Repository $repository)
    {
        return $this->buildAsDeep($repository);
    }

    /**
     * @return $this
     */
    public function notWrapper()
    {
        $this->variables['wrapper'] = false;
        return $this;
    }
}
