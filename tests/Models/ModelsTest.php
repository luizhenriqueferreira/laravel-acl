<?php

namespace LuizHenriqueBK\LaravelAcl\Tests\Models;

use LuizHenriqueBK\LaravelAcl\Tests\TestCase;

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
