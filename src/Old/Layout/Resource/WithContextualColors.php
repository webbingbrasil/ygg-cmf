<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 30/10/2019
 * Time: 11:00
 */

namespace  Ygg\Old\Layout\Resource;

use Illuminate\Support\Arr;
use Ygg\Old\Traits\Transformers\AttributeTransformer;
use Closure;

/**
 * Class ContextualColors
 * @method self setCustomTransformer(string $attribute, string|AttributeTransformer|Closure $transformer)
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
 * @package Ygg\Old\Layout\Resource
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
     * @return void
     */
    protected function bootWithContextualColors(): void
    {
        $this->setCustomTransformer('rowClass', function($value, $row) {
            foreach ($this->contextCallbacks as $context => $callback) {
                $result = $callback($row);
                if($result) {
                    return array_merge($this->getContextClasses($context), (array) $value);
                }
            }

            return $this->getContextClasses('default');
        });
    }

    /**
     * @param string $context
     * @return array
     */
    protected function getContextClasses(string $context): array
    {
        return Arr::get(self::$contextClasses, $context, []);
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
