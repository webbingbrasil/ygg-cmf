<?php


namespace Ygg\Screen;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use Ygg\Actions\WithActions;
use Ygg\Filters\WithFilters;
use Ygg\Platform\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ygg\Screen\Layouts\Base;

abstract class Screen extends Controller
{
    use WithActions, WithFilters;

    const ROUTE_VARIABLEs_NUMBER = 2;

    /**
     * Display header name.
     *
     * @var string
     */
    public $name;

    /**
     * Display header description.
     *
     * @var string
     */
    public $description;

    /**
     * Indicates if should be displayed in the sidebar.
     *
     * @var bool
     */
    public $displayInNavigation = false;

    /**
     * The logical group associated with the screen.
     *
     * @var string
     */
    public $group;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * Screen constructor.
     *
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request ?? request();
    }

    /**
     * @return array
     */
    abstract public function layout(): array;

    /**
     * @param mixed ...$parameters
     * @return View|\Illuminate\Http\RedirectResponse|mixed
     * @throws ReflectionException
     */
    public function handle(...$parameters)
    {
        abort_if(!$this->hasPermission(), 403);

        if($this->request->method() === 'GET' || !count($parameters)) {
            $this->arguments = $parameters;
            return $this->redirectOnGetOrShowView();
        }

        $method = array_pop($parameters);
        $this->arguments = $parameters;

        if(Str::startsWith($method, 'async')) {
            return $this->asyncBuild($method, array_pop($this->arguments));
        }

        $this->reflectionParams($method);
        return call_user_func_array([$this, $method], $this->arguments);
    }

    /**
     * @return mixed
     */
    public function build()
    {
        return Layout::blank([
            $this->layout()
        ])->build($this->repository);
    }

    /**
     * @param string $method
     * @param string $slugLayout
     * @return mixed
     * @throws ReflectionException
     */
    protected function asyncBuild(string $method, string $slugLayout)
    {
        $this->arguments = $this->request->json()->all();
        $repository = $this->createRepository($method);

        /** @var Base $layout */
        $layout = collect($this->layout())
            ->map(static function ($layout) {
                return is_object($layout) ? $layout : app()->make($layout);
            })
            ->filter(static function (Base $layout) use ($slugLayout) {
                return $layout->getSlug() === $slugLayout;
            })
            ->whenEmpty(static function () use ($method) {
                abort(404, $method . ' not found');
            })
            ->first();

        return $layout->currentAsync()->build($repository);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     * @throws ReflectionException
     */
    public function view(): View
    {
        $this->repository = $this->createRepository('query');
        $actions = $this->buildActions($this->repository);
        $filters = $this->buildFilters($this->repository);

        return view()->make('platform::layouts.base', [
            'screen' => $this,
            'actions' => $actions,
            'filters' => $filters,
        ]);
    }

    /**
     * @param string $method
     * @return Repository
     * @throws ReflectionException
     */
    private function createRepository(string $method): Repository
    {
        $this->reflectionParams($method);
        $query = call_user_func_array([$this, $method], $this->arguments);
        return new Repository($query);
    }

    /**
     * @param string $method
     * @throws ReflectionException
     */
    private function reflectionParams(string $method): void
    {
        $class = new ReflectionClass($this);

        if(!is_string($method) || !$class->hasMethod($method)) {
            return;
        }

        $parameters = $class->getMethod($method)->getParameters();

        $this->arguments = collect($parameters)
            ->map(function ($parameter, $key) {
                return $this->bind($key, $parameter);
            })->all();
    }

    /**
     * @param int $key
     * @param ReflectionParameter $parameter
     * @return mixed|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bind(int $key, ReflectionParameter $parameter)
    {
        $class = optional($parameter->getClass())->name;
        $original = array_values($this->arguments)[$key] ?? null;

        if($class === null || is_object($original)) {
            return $original;
        }

        $object = app()->make($class);

        if($original !== null && is_a($object, UrlRoutable::class)) {
            return $object->resolveRouteBinding($original);
        }

        return $object;
    }

    /**
     * @return bool
     */
    protected function hasPermission()
    {
        return property_exists($this, 'permission') ? $this->checkPermission($this->permission) : true;
    }

    /**
     * @return string
     */
    public function formValidateMessage(): string
    {
        return __('Please check the entered data, it may be necessary to specify in other languages.');
    }

    /**
     * @return View|\Illuminate\Http\RedirectResponse
     * @throws ReflectionException
     */
    private function redirectOnGetOrShowView()
    {
        $expectedArgNumber = count($this->request->route()->getCompiled()->getVariables()) - self::ROUTE_VARIABLEs_NUMBER;
        $realArgNumber = count($this->arguments);

        if($realArgNumber <= $expectedArgNumber) {
            return $this->view();
        }

        array_pop($this->arguments);
        return redirect()->action([static::class, 'handle'], $this->arguments);
    }
}
