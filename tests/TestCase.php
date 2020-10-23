<?php

namespace LuizHenriqueBK\LaravelAcl\Tests;

use LuizHenriqueBK\LaravelAcl\LaravelAclServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Laravel Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelAclServiceProvider::class,
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */

    /**
     * Get the migrations source path.
     *
     * @return string
     */
    protected function getMigrationsSrcPath()
    {
        return realpath(dirname(__DIR__) . '/src/Migrations');
    }

    /**
     * Migrate the migrations.
     */
    protected function migrate()
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom($this->getMigrationsSrcPath());
    }
}
