<?php

namespace LuizHenriqueFerreira\LaravelAcl\Tests\Models;

use LuizHenriqueFerreira\LaravelAcl\Tests\TestCase;

abstract class ModelsTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->migrate();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
