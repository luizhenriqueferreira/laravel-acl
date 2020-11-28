<?php

namespace LuizHenriqueFerreira\LaravelAcl\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class Role
 * @package LuizHenriqueFerreira\LaravelAcl\Models
 */
class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'name', 'description'
    ];

    #########################
    ####  RELATIONSHIPS  ####
    #########################

    /**
     * A user can be applied to roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        $model = config('auth.providers.users.model');

        if (! class_exists($model)) {
            throw new Exception("User class {$model} not found!", 1);
        }

        return $this->belongsToMany($model);
    }

    /**
     * A permission can be applied to roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    #########################
    ####     METHODS     ####
    #########################

    /**
     * Attach Permissions
     *
     * @param mixed  $permissions
     * @return $this
     */
    public function attachPermission(...$permissions)
    {
        $permissions = Arr::flatten($permissions);
        foreach ($permissions as $permission) {
            $permission = $permission instanceof Permission ? $permission : Permission::whereIdOrName($permission, $permission)->first();

            if ($permission && !$this->permissions->contains('name', $permission->name)) {
                $this->permissions()->attach($$permission->id);
            }
        }

        return $this;
    }

    /**
     * Detach Permissions
     *
     * @param mixed  $permissions
     * @return $this
     */
    public function detachPermission(...$permissions)
    {
        if (count($permissions)) {
            $permissions = Arr::flatten($permissions);
            foreach ($permissions as $permission) {
                $permission = $permission instanceof Permission ? $permission : Permission::whereIdOrName($permission, $permission)->first();

                if ($permission && $this->permissions->contains('name', $permission->name)) {
                    $this->permissions()->detach($$permission->id);
                }
            }
        } else {
            $this->permissions()->detach();
        }

        return $this;
    }

    /**
     * Sync Permissions
     *
     * @param mixed  $roles
     * @return $this
     */
    public function syncPermissions(...$permissions)
    {
        $sync = [];
        $permissions = Arr::flatten($permissions);
        foreach ($permissions as $permission) {
            $permission = $permission instanceof Permission ? $permission : Permission::whereIdOrName($permission, $permission)->first();
            $sync[] = $permission->id;
        }

        if (count($sync)) {
            $this->permissions()->sync($sync);
        }

        return $this;
    }
}
