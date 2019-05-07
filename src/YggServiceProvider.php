<?php

namespace Ygg;

use Ygg\Auth\AuthorizationManager;
use Ygg\Form\Eloquent\Uploads\Migration\CreateUploadsMigration;
use Ygg\Composers\AssetViewComposer;
use Ygg\Composers\MenuViewComposer;
use Ygg\Http\Middleware\Api\AddContext;
use Ygg\Http\Middleware\Api\AppendFormAuthorizations;
use Ygg\Http\Middleware\Api\AppendListAuthorizations;
use Ygg\Http\Middleware\Api\AppendListMultiForm;
use Ygg\Http\Middleware\Api\AppendNotifications;
use Ygg\Http\Middleware\Api\BindValidationResolver;
use Ygg\Http\Middleware\Api\HandleApiErrors;
use Ygg\Http\Middleware\Api\SaveResourceListParams;
use Ygg\Http\Middleware\Api\YggSetLocale;
use Ygg\Http\Middleware\RestoreResourceListParams;
use Ygg\Http\Middleware\YggAuthenticate;
use Ygg\Http\Middleware\YggRedirectIfAuthenticated;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\Facades\Gate;
use Ygg\Http\Context;
use Illuminate\Support\ServiceProvider;

/**
 * Class YggServiceProvider
 * @package Ygg
 */
class YggServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ygg');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/back', 'ygg');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/front', 'ygg-front');

        $this->registerPolicies();

        view()->composer(
            ['ygg::form', 'ygg::list', 'ygg::dashboard', 'ygg::welcome'],
            MenuViewComposer::class
        );

        view()->composer(
            ['ygg::form', 'ygg::list', 'ygg::dashboard', 'ygg::welcome', 'ygg::login', 'ygg::unauthorized'],
            AssetViewComposer::class
        );
    }

    public function register(): void
    {
        $this->registerMiddleware();

        $this->app->singleton(
            Context::class, Context::class
        );

        $this->app->singleton(
            AuthorizationManager::class, AuthorizationManager::class
        );

        // Override Laravel's Gate to handle Sharp's ability to define a custom Guard
        $this->app->singleton(GateContract::class, function ($app) {
            return new \Illuminate\Auth\Access\Gate($app, function () use ($app) {
                return request()->is('ygg') || request()->is('ygg/*')
                    ? auth()->user()
                    : auth()->guard(config('auth.defaults.guard'))->user();
            });
        });

        $this->commands([
            CreateUploadsMigration::class,
        ]);
    }

    protected function registerPolicies(): void
    {
        foreach((array)config('ygg.resources') as $resourceKey => $config) {
            if(isset($config['policy'])) {
                foreach(['resource', 'view', 'update', 'create', 'delete'] as $action) {
                    $this->definePolicy($resourceKey, $config['policy'], $action);
                }
            }
        }

        foreach((array)config('ygg.dashboards') as $dashboardKey => $config) {
            if(isset($config['policy'])) {
                $this->definePolicy($dashboardKey, $config['policy'], 'view');
            }
        }
    }

    /**
     * @param string $resourceKey
     * @param string $policy
     * @param string $action
     */
    protected function definePolicy($resourceKey, $policy, $action): void
    {
        if(method_exists(app($policy), $action)) {
            Gate::define('ygg.'.$resourceKey.'.'.$action, $policy . '@'.$action);

        } else {
            // No policy = true by default
            Gate::define('ygg.'.$resourceKey.'.'.$action, function () {
                return true;
            });
        }
    }

    protected function registerMiddleware(): void
    {
        $this->app['router']->middlewareGroup('ygg_web', [
            \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $this->app['router']->aliasMiddleware(
            'ygg_api_append_form_authorizations', AppendFormAuthorizations::class

        )->aliasMiddleware(
            'ygg_api_append_list_authorizations', AppendListAuthorizations::class

        )->aliasMiddleware(
            'ygg_api_append_list_multiform', AppendListMultiForm::class

        )->aliasMiddleware(
            'ygg_api_append_notifications', AppendNotifications::class

        )->aliasMiddleware(
            'ygg_api_errors', HandleApiErrors::class

        )->aliasMiddleware(
            'ygg_api_context', AddContext::class

        )->aliasMiddleware(
            'ygg_api_validation', BindValidationResolver::class

        )->aliasMiddleware(
            'ygg_locale', YggSetLocale::class

        )->aliasMiddleware(
            'ygg_save_list_params', SaveResourceListParams::class

        )->aliasMiddleware(
            'ygg_restore_list_params', RestoreResourceListParams::class

        )->aliasMiddleware(
            'ygg_auth', YggAuthenticate::class

        )->aliasMiddleware(
            'ygg_guest', YggRedirectIfAuthenticated::class
        );
    }
}
