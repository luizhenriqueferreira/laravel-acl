<?php

namespace LuizHenriqueBK\LaravelAcl;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class LaravelAclServiceProvider
 *
 * @package LuizHenriqueBK\LaravelAcl
 */
class LaravelAclServiceProvider extends ServiceProvider
{
    /**
     * Commands
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        $this->initMigrations();
        $this->initPermissions();
        $this->initMiddlewares();
        $this->initDirectives();
    }

    /**
     * Initialization migrations
     *
     * @return void
     */
    protected function initMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    /**
     * Initialization permissions
     *
     * @return void
     */
    protected function initPermissions()
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        DB::table('permissions')->get(['name'])->map(function ($permission) {
            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->hasPermission($permission->name);
            });
        });
    }

    /**
     * Initialization middlewares
     *
     * @return void
     */
    protected function initMiddlewares()
    {
        Route::aliasMiddleware('role', Middleware\RoleMiddleware::class);
        Route::aliasMiddleware('permission', Middleware\PermissionMiddleware::class);
    }

    /**
     * Initialization directives
     *
     * @return void
     */
    protected function initDirectives()
    {
        Blade::directive('hasrole', function ($role) {
            return "<?php if (auth()->check() && auth()->user()->hasRole({$role})) : ?>";
        });
        Blade::directive('elsehasrole', function ($role) {
            return "<?php elseif ($role): ?>";
        });
        Blade::directive('endhasrole', function () {
            return "<?php endif; ?>";
        });
    }
}
