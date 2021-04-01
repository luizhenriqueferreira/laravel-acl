<?php

namespace LuizHenriqueFerreira\LaravelAcl\Models\Traits;

use Illuminate\Support\Arr;
use LuizHenriqueFerreira\LaravelAcl\Models\Role;
use LuizHenriqueFerreira\LaravelAcl\Models\Permission;

/**
 * Trait HasRoles
 *
 * @package LuizHenriqueFerreira\LaravelAcl\Models\Traits
 */
trait HasRoles
{
    #########################
    ####  RELATIONSHIPS  ####
    #########################

    /**
     * A model may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles()
    {
        return $this->morphToMany(Role::class, 'roleable')->with(['permissions']);
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
    public function hasRoles(...$roles)
    {
        $roles = Arr::flatten($roles);
        foreach ($roles as $role) {
            $slug = $role instanceof Role ? $role->slug : $role;
            return $this->roles->contains('slug', $slug);
        }

        return false;
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  mixed  $permissions
     * @return boolean
     */
    public function hasPermissions(...$permissions)
    {
        $permissions = Arr::flatten($permissions);
        foreach ($permissions as $permission) {
            $slug = $permission instanceof Permission ? $permission->slug : $permission;
            return $this->permissions()->contains('slug', $slug);
        }

        return false;
    }

    /**
      * Attach role
      *
      * @param mixed  $roles
      * @return $this
      */
    public function attachRoles(...$roles)
    {
        $roles = Arr::flatten($roles);

        foreach ($roles as $role) {
            $role = $role instanceof Role ? $role : Role::whereIdOrSlug($role, $role)->first();

            if ($role && !$this->roles->contains('slug', $role->slug)) {
                $this->roles()->attach($role);
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
    public function detachRoles(...$roles)
    {
        if (count($roles)) {
            $roles = Arr::flatten($roles);
            foreach ($roles as $role) {
                $role = $role instanceof Role ? $role : Role::whereIdOrSlug($role, $role)->first();

                if ($role && $this->roles->contains('slug', $role->slug)) {
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
            $role = $role instanceof Role ? $role : Role::whereIdOrSlug($role, $role)->first();
            $sync[] = $role->id;
        }

        if (count($sync)) {
            $this->roles()->sync($sync);
        }

        return $this;
    }
}
