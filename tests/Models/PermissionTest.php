<?php

namespace LuizHenriqueFerreira\LaravelAcl\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use LuizHenriqueFerreira\LaravelAcl\Models\Permission;
use LuizHenriqueFerreira\LaravelAcl\Models\Role;

class PermissionTest extends ModelsTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Permission */
    protected $model;

    /** @var $attributes */
    protected $attributes = [
        'title'       => 'Create users',
        'name'        => 'users.create',
        'description' => 'Allow to create users',
    ];

    /** @var updatedAttributes */
    protected $updatedAttributes = [
        'title'       => 'Update users',
        'name'        => 'users.update',
        'description' => 'Allow to update users',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->migrate();

        $this->model = new Permission;
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->model);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function itCanBeInstantiated()
    {
        $expectations = [
            Model::class,
            Permission::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->model);
        }
    }

    /** @test */
    public function itHasRelationships()
    {
        $rolesRelationship = $this->model->roles();

        $this->assertInstanceOf(BelongsToMany::class, $rolesRelationship);
        $this->assertInstanceOf(Role::class, $rolesRelationship->getRelated());
    }

    /** @test */
    public function itCanCreate()
    {
        $permission = $this->model->create($this->attributes);

        $this->assertEquals($this->attributes['title'], $permission->title);
        $this->assertEquals($this->attributes['name'], $permission->name);
        $this->assertEquals($this->attributes['description'], $permission->description);
        $this->assertDatabaseHas('permissions', $this->attributes);
    }

    /** @test */
    public function itCanUpdate()
    {
        $permission = $this->model->create($this->attributes);
        $this->assertDatabaseHas('permissions', $this->attributes);

        $permission->update($this->updatedAttributes);
        $this->assertDatabaseHas('permissions', $this->updatedAttributes);
        $this->assertDatabaseMissing('permissions', $this->attributes);
    }

    /** @test */
    public function itCanDelete()
    {
        $permission = $this->model->create($this->attributes);
        $this->assertDatabaseHas('permissions', $this->attributes);

        $permission->delete();
        $this->assertDatabaseMissing('permissions', $this->attributes);
    }
}
