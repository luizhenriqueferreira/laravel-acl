<?php

namespace LuizHenriqueFerreira\LaravelAcl\Tests;

use Illuminate\Support\Facades\Schema;

class MigrationTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function itCanMigrate()
    {
        $this->migrate();

        foreach (['users', 'roles', 'roleables', 'permissions', 'permission_role'] as $table) {
            $this->assertTrue(Schema::hasTable($table), "The table [$table] not found in the database.");
        }
    }
}
