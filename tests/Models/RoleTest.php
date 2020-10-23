<?php

namespace LuizHenriqueBK\LaravelAcl\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use LuizHenriqueBK\LaravelAcl\Models\Permission;
use LuizHenriqueBK\LaravelAcl\Models\Role;
use LuizHenriqueBK\LaravelAcl\Models\User;

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
        'name'        => 'administrador',
        'description' => 'Administrator role description.',
    ];

    /** @var $updatedAttributes */
    protected $updatedAttributes = [
        'title'       => 'Moderator',
        'name'        => 'moderator',
        'description' => 'Moderator role description.',
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

    /** @test */
    public function itHasRelationships()
    {
        $usersRelationship       = $this->model->users();
        $permissionsRelationship = $this->model->permissions();

        $this->assertInstanceOf(BelongsToMany::class, $usersRelationship);
        $this->assertInstanceOf(BelongsToMany::class, $permissionsRelationship);

        /**
         * @var  User        $user
         * @var  Permission  $permission
         */
        $user       = $usersRelationship->getRelated();
        $permission = $permissionsRelationship->getRelated();

        $this->assertInstanceOf(config('auth.providers.users.model'), $user);
        $this->assertInstanceOf(Permission::class, $permission);
    }

    /** @test */
    public function itCanCreate()
    {
        $role = $this->model->create($this->attributes);
        $this->assertEquals($this->attributes['title'], $role->title);
        $this->assertEquals($this->attributes['name'], $role->name);
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
}
