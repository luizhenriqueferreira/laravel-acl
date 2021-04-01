<?php

namespace LuizHenriqueFerreira\LaravelAcl\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Schema;
use LuizHenriqueFerreira\LaravelAcl\Models\Permission;
use LuizHenriqueFerreira\LaravelAcl\Models\Role;
use LuizHenriqueFerreira\LaravelAcl\Models\User;

class RoleTest extends ModelsTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Role */
    protected $model;

    /** @var $attributes */
    protected $attributes = [
        'title'       => 'Administrator',
        'slug'        => 'administrador',
        'description' => 'Administrator role description.',
    ];

    /** @var $updatedAttributes */
    protected $updatedAttributes = [
        'title'       => 'Moderator',
        'slug'        => 'moderator',
        'description' => 'Moderator role description.',
    ];

    /** @var $permissionAttributes */
    protected $permissionAttributes = [
        'title'       => 'Create users',
        'slug'        => 'users.create',
        'description' => 'Allow to create users',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->model = new Role;
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
            Role::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->model);
        }
    }

    /** @test  */
    public function itDatabaseHasExpectedColumns()
    {
        $this->assertTrue(Schema::hasColumns($this->model->getTable(), array_keys($this->attributes)));
        $this->assertTrue(Schema::hasColumns('roleables', ['role_id', 'roleable_id', 'roleable_type']));
    }

    /** @test */
    public function itHasRelationships()
    {
        $permissionsRelationship = $this->model->permissions();
        $permission = $permissionsRelationship->getRelated();

        $this->assertInstanceOf(Permission::class, $permission);
        $this->assertInstanceOf(BelongsToMany::class, $permissionsRelationship);
    }

    /** @test */
    public function itCanCreate()
    {
        $role = $this->model->create($this->attributes);
        $this->assertEquals($this->attributes['title'], $role->title);
        $this->assertEquals($this->attributes['slug'], $role->slug);
        $this->assertEquals($this->attributes['description'], $role->description);
        $this->assertDatabaseHas('roles', $this->attributes);
    }

    /** @test */
    public function itCanUpdate()
    {
        $role = $this->model->create($this->attributes);
        $this->assertDatabaseHas('roles', $this->attributes);

        $role->update($this->updatedAttributes);
        $this->assertDatabaseMissing('roles', $this->attributes);
        $this->assertDatabaseHas('roles', $this->updatedAttributes);
    }

    /** @test */
    public function itCanDelete()
    {
        $role = $this->model->create($this->attributes);
        $this->assertDatabaseHas('roles', $this->attributes);

        $role->delete();
        $this->assertDatabaseMissing('roles', $this->attributes);
    }

    /** @test */
    public function itCanAttachPermissions()
    {
        $permission = (new Permission)->create($this->permissionAttributes);

        $role = (new Role)->create($this->attributes);
        $role->syncPermissions($permission)->load('permissions');

        $this->assertEquals($role->permissions->first()->title, $permission->title);
        $this->assertEquals($role->permissions->first()->slug, $permission->slug);
        $this->assertEquals($role->permissions->first()->description, $permission->description);
    }

    /** @test */
    public function itCanDetachPermissions()
    {
        $permission = (new Permission)->create($this->permissionAttributes);

        $role = (new Role)->create($this->attributes);
        $role->syncPermissions($permission)->load('permissions');

        $this->assertEquals($role->permissions->first()->title, $permission->title);
        $this->assertEquals($role->permissions->first()->slug, $permission->slug);
        $this->assertEquals($role->permissions->first()->description, $permission->description);

        $role->detachPermissions($permission)->load('permissions');

        $this->assertEquals(0, $role->permissions->count());
    }

    /** @test */
    public function itCanSyncPermissions()
    {
        $permission = (new Permission)->create($this->permissionAttributes);

        $role = (new Role)->create($this->attributes);
        $role->syncPermissions($permission)->load('permissions');

        $this->assertEquals($role->permissions->first()->title, $permission->title);
        $this->assertEquals($role->permissions->first()->slug, $permission->slug);
        $this->assertEquals($role->permissions->first()->description, $permission->description);
    }
}
