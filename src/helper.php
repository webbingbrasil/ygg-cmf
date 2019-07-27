<?php

use Ygg\Exceptions\Auth\AuthorizationException;

/**
 * @return string
 */
function ygg_title()
{
    return config('ygg.name', config('app.name', 'Ygg'));
}

/**
 * @return float
 */
function ygg_version()
{
    return '1.0.0';
}

/**
 * @return string
 */
function ygg_admin_base_url()
{
    return config('ygg.admin_base_url', 'admin');
}

/**
 * @param array $yggMenu
 * @param string|null $entityKey
 * @return string
 */
function ygg_page_title($yggMenu, $entityKey)
{
    $title = 'Admin';

    if(request()->is(ygg_admin_base_url() . '/login')) {
        $title = trans('ygg::login.login_page_title');

    } elseif ($yggMenu) {
        $menuItems = collect($yggMenu->menuItems);

        // Handle MultiForms
        $entityKey = explode(':', $entityKey)[0];

        $label = $menuItems
                ->where('type', 'entity')
                ->firstWhere('key', $entityKey)
                ->label ?? "";

        if(!$label) {
            $label = $menuItems
                    ->where('type', 'category')
                    ->pluck('entities')
                    ->flatten()
                    ->firstWhere('key', $entityKey)
                    ->label ?? '';
        }

        $title = $yggMenu->name . ($label ? ', ' . $label : '');
    }

    return config('ygg.display_ygg_version_in_title', true)
        ? "$title | Ygg " . ygg_version()
        : $title;
}

/**
 * @return string
 */
function ygg_custom_fields()
{
    return '';
}

/**
 * @param string      $ability
 * @param string      $resourceKey
 * @param string|null $instanceId
 * @return bool
 */
function ygg_has_ability(string $ability, string $resourceKey, string $instanceId = null)
{
    try {
        ygg_check_ability($ability, $resourceKey, $instanceId);
        return true;

    } catch (AuthorizationException $ex) {
        return false;
    }
}

/**
 * @param string      $ability
 * @param string      $resourceKey
 * @param string|null $instanceId
 */
function ygg_check_ability(string $ability, string $resourceKey, string $instanceId = null)
{
    app(Ygg\Auth\AuthorizationManager::class)
        ->check($ability, $resourceKey, $instanceId);
}

/**
 * Return true if the $handler class actually implements the $methodName method;
 * return false if the method is defined as concrete in a super class and not overridden.
 *
 * @param        $handler
 * @param string $methodName
 * @return bool
 */
function is_method_implemented_in_concrete_class($handler, string $methodName)
{
    try {
        $foo = new \ReflectionMethod(get_class($handler), $methodName);
        $declaringClass = $foo->getDeclaringClass()->getName();

        return $foo->getPrototype()->getDeclaringClass()->getName() !== $declaringClass;

    } catch (\ReflectionException $e) {
        return false;
    }
}
