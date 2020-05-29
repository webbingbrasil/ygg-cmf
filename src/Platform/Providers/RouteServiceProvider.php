<?php

namespace Ygg\Platform\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Ygg\Platform\Http\Middleware\AccessMiddleware;
use Ygg\Platform\MenuActive;
use Ygg\Platform\Models\Role;
use Ygg\Platform\Dashboard;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @internal param Router $router
     */
    public function boot()
    {
        Route::middlewareGroup('platform', [
            AccessMiddleware::class,
        ]);

        $this->binding();

        require Dashboard::path('routes/breadcrumbs.php');

        parent::boot();
    }

    /**
     * Route binding.
     */
    public function binding()
    {
        Route::bind('roles', static function ($value) {
            $role = Dashboard::modelClass(Role::class);

            return is_numeric($value)
                ? $role->where('id', $value)->firstOrFail()
                : $role->where('slug', $value)->firstOrFail();
        });

        $this->app->bind('active', MenuActive::class);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        /*
         * Public
         */
        Route::domain((string) config('platform.domain'))
            ->prefix(Dashboard::prefix('/'))
            ->as('platform.')
            ->group(Dashboard::path('routes/public.php'));

        /*
         * Dashboard
         */
        Route::domain((string) config('platform.domain'))
            ->prefix(Dashboard::prefix('/'))
            ->as('platform.')
            ->middleware(config('platform.middleware.private'))
            ->group(Dashboard::path('routes/dashboard.php'));

        /*
         * Auth
         */
        Route::domain((string) config('platform.domain'))
            ->prefix(Dashboard::prefix('/'))
            ->as('platform.')
            ->middleware(config('platform.middleware.public'))
            ->group(Dashboard::path('routes/auth.php'));

        /*
         * Systems
         */
        Route::domain((string) config('platform.domain'))
            ->prefix(Dashboard::prefix('/systems'))
            ->as('platform.')
            ->middleware(config('platform.middleware.private'))
            ->group(Dashboard::path('routes/systems.php'));

        /*
         * Application
         */
        if (file_exists(base_path('routes/platform.php'))) {
            Route::domain((string) config('platform.domain'))
                ->prefix(Dashboard::prefix('/'))
                ->middleware(config('platform.middleware.private'))
                ->group(base_path('routes/platform.php'));
        }
    }
}
