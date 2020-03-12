<?php

namespace Ygg\Screen;

use Illuminate\Support\Arr;
use Closure;

/**
 * Trait WithContextualColors
 * @method self isActiveWhen(Closure $callback)
 * @method self isDefaultWhen(Closure $callback)
 * @method self isPrimaryWhen(Closure $callback)
 * @method self isSecondaryWhen(Closure $callback)
 * @method self isSuccessWhen(Closure $callback)
 * @method self isDangerWhen(Closure $callback)
 * @method self isWarningWhen(Closure $callback)
 * @method self isInfoWhen(Closure $callback)
 * @method self isLightWhen(Closure $callback)
 * @method self isDarkWhen(Closure $callback)
 * @package Ygg\Screen
 */
trait WithContextualColors
{
    /**
     * @var array
     */
    static protected $contextClasses = [
        'active' => ['row-active'],
        'default' => ['row-default'],
        'primary' => ['row-primary'],
        'secondary' => ['row-secondary'],
        'success' => ['row-success'],
        'danger' => ['row-danger'],
        'warning' => ['row-warning'],
        'info' => ['row-info'],
        'light' => ['row-light'],
        'dark' => ['row-dark'],
    ];
    /**
     * @var array
     */
    protected $contextCallbacks = [];

    /**
     * @param $source
     * @return array
     */
    protected function buildContextClass($source): array
    {
        foreach ($this->contextCallbacks as $context => $callback) {
            $result = $callback($source);
            if($result) {
                return $this->getContextClasses($context);
            }
        }

        return $this->getContextClasses('default');
    }

    /**
     * @param string $context
     * @return array
     */
    protected function getContextClasses(string $context): array
    {
        return (array) Arr::get(self::$contextClasses, $context, []);
    }

    /**
     * @param string  $context
     * @param Closure $callback
     */
    public function setContextCallback(string $context, Closure $callback): void
    {
        $this->contextCallbacks[$context] = $callback;
    }

    /**
     * @param $name
     * @param $arguments
     * @return self
     */
    public function __call($name, $arguments)
    {
        $context = strtolower(preg_replace(['/is/', '/When/'], '', $name));
        if(array_key_exists($context, self::$contextClasses)) {
            $this->setContextCallback($context, Arr::first($arguments));
        }
        return $this;
    }
}
