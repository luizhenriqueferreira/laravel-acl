<?php

namespace LuizHenriqueBK\LaravelAcl\Models\Traits;

use Illuminate\Support\Arr;
use LuizHenriqueBK\LaravelAcl\Models\Role;
use LuizHenriqueBK\LaravelAcl\Models\Permission;

trait HasRoles
{
    #########################
    ####  RELATIONSHIPS  ####
    #########################

    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->with(['permissions']);
    }

    #########################
    ####     METHODS     ####
    #########################

    /**
     * A user may have multiple Permission.
     *
     * @return array
     */
    public function permissions()
    {
        return $this->roles->pluck('permissions')->collapse();
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $roles
     * @return boolean
     */
    public function hasRole(...$roles)
    {
        $roles = Arr::flatten($roles);
        foreach ($roles as $role) {
            $name = $role instanceof Role ? $role->name : $role;
            return $this->roles->contains('name', $name);
        }

        return false;
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  mixed  $permissions
     * @return boolean
     */
    public function hasPermission(...$permissions)
    {
        $permissions = Arr::flatten($permissions);
        foreach ($permissions as $permission) {
            $name = $permission instanceof Permission ? $permission->name : $permission;
            return $this->permissions()->contains('name', $name);
        }

        return false;
    }

    /**
      * Attach role
      *
      * @param mixed  $roles
      * @return $this
      */
    public function attachRole(...$roles)
    {
        $roles = Arr::flatten($roles);
        foreach ($roles as $role) {
            $role = $role instanceof Role ? $role : Role::whereIdOrName($role, $role)->first();

            if ($role && !$this->roles->contains('name', $role->name)) {
                $this->roles()->attach($role->id);
            }
        }

        return $this;
    }

    /**
     * Detach role
     *
     * @param mixed  $roles
     * @return $this
     */
    public function detachRole(...$roles)
    {
        if (count($roles)) {
            $permissions = Arr::flatten($permissions);
            foreach ($roles as $role) {
                $role = $role instanceof Role ? $role : Role::whereIdOrName($role, $role)->first();

                if ($role && $this->roles->contains('name', $role->name)) {
                    $this->roles()->detach($role->id);
                }
            }
        } else {
            $this->roles()->detach();
        }

        return $this;
    }

    /**
     * Sync roles
     *
     * @param mixed  $roles
     * @return $this
     */
    public function syncRoles(...$roles)
    {
        $sync = [];
        $roles = Arr::flatten($roles);
        foreach ($roles as $role) {
            $role = $role instanceof Role ? $role : Role::whereIdOrName($role, $role)->first();
            $sync[] = $role->id;
        }

        if (count($sync)) {
            $this->roles()->sync($sync);
        }

        return $this;
    }
}
