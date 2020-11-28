<?php

namespace LuizHenriqueFerreira\LaravelAcl\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use LuizHenriqueFerreira\LaravelAcl\Models\Permission;
use LuizHenriqueFerreira\LaravelAcl\Models\Role;
use LuizHenriqueFerreira\LaravelAcl\Models\User;

class UserTest extends ModelsTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var User */
    protected $model;

    /** @var $attributes */
    protected $attributes = [
        'name'     => 'User',
        'email'    => 'user@example.com',
        'password' => 'password',
    ];

    /** @var $roleAttributes */
    protected $roleAttributes = [
        'title'       => 'Administrator',
        'name'        => 'administrador',
        'description' => 'Administrator role description.',
    ];

    /** @var $permissionAttributes */
    protected $permissionAttributes = [
        'title'       => 'Create users',
        'name'        => 'users.create',
        'description' => 'Allow to create users',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->model = new User;
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
            User::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->model);
        }
    }

    /** @test */
    public function itCanAttachRole()
    {
        $role = (new Role)->create($this->roleAttributes);

        $user = (new User)->fill($this->attributes);
        $user->save();
        $user->syncRoles($role);

        $this->assertEquals($user->roles->first()->title, $role->title);
        $this->assertEquals($user->roles->first()->name, $role->name);
        $this->assertEquals($user->roles->first()->description, $role->description);
    }

    /** @test */
    public function itCanAttachRoleAndPermission()
    {
        $permission = (new Permission)->create($this->permissionAttributes);

        $role       = (new Role)->create($this->roleAttributes);
        $role->syncPermissions($permission);

        $user = (new User)->fill($this->attributes);
        $user->save();
        $user->syncRoles($role);

        $this->assertEquals($user->roles->first()->title, $role->title);
        $this->assertEquals($user->roles->first()->name, $role->name);
        $this->assertEquals($user->roles->first()->description, $role->description);

        $this->assertEquals($user->permissions()->first()->title, $permission->title);
        $this->assertEquals($user->permissions()->first()->name, $permission->name);
        $this->assertEquals($user->permissions()->first()->description, $permission->description);
    }
}
