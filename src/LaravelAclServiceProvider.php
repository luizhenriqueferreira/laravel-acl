<?php

namespace LuizHenriqueFerreira\LaravelAcl;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LuizHenriqueFerreira\LaravelAcl\Models\Role;
use LuizHenriqueFerreira\LaravelAcl\Models\Permission;

/**
 * Class LaravelAclServiceProvider
 *
 * @package LuizHenriqueFerreira\LaravelAcl
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

        Relation::morphMap([
            'Role' => Role::class,
            'Permission' => Permission::class,
        ]);
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

        DB::table('permissions')->get(['slug'])->map(function ($permission) {
            Gate::define($permission->slug, function ($user) use ($permission) {
                return $user->hasPermissions($permission->slug);
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
        Blade::directive('hasroles', function ($role) {
            return "<?php if (auth()->check() && auth()->user()->hasRoles({$role})) : ?>";
        });
        Blade::directive('elsehasroles', function ($role) {
            return "<?php elseif ($role): ?>";
        });
        Blade::directive('endhasroles', function () {
            return "<?php endif; ?>";
        });
    }
}
