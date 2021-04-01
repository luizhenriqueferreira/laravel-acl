<?php

namespace LuizHenriqueFerreira\LaravelAcl\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
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
        'slug'        => 'administrador',
        'description' => 'Administrator role description.',
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
    public function itHasRelationships()
    {
        $rolesRelationship = $this->model->roles();
        $role = $rolesRelationship->getRelated();

        $this->assertInstanceOf(MorphToMany::class, $rolesRelationship);
        $this->assertInstanceOf(Role::class, $role);
    }

    /** @test */
    public function itCanAttachRoles()
    {
        $role = (new Role)->create($this->roleAttributes);

        $user = (new User)->fill($this->attributes);
        $user->save();
        $user->attachRoles($role)->load('roles');

        $this->assertEquals($user->roles->first()->title, $role->title);
        $this->assertEquals($user->roles->first()->slug, $role->slug);
        $this->assertEquals($user->roles->first()->description, $role->description);
    }

    /** @test */
    public function itCanSynchRoles()
    {
        $role = (new Role)->create($this->roleAttributes);

        $user = (new User)->fill($this->attributes);
        $user->save();
        $user->syncRoles($role)->load('roles');

        $this->assertEquals($user->roles->first()->title, $role->title);
        $this->assertEquals($user->roles->first()->slug, $role->slug);
        $this->assertEquals($user->roles->first()->description, $role->description);
    }

    /** @test */
    public function itCanDetachRoles()
    {
        $role = (new Role)->create($this->roleAttributes);

        $user = (new User)->fill($this->attributes);
        $user->save();
        $user->attachRoles($role)->load('roles');

        $this->assertEquals($user->roles->first()->title, $role->title);
        $this->assertEquals($user->roles->first()->slug, $role->slug);
        $this->assertEquals($user->roles->first()->description, $role->description);

        $user->detachRoles($role)->load('roles');

        $this->assertEquals(0, $user->roles->count());
    }
}
