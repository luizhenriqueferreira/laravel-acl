<?php

namespace LuizHenriqueFerreira\LaravelAcl\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class Role
 *
 * @package LuizHenriqueFerreira\LaravelAcl\Models
 */
class Role extends Model
{
    use Traits\HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description'
    ];

    #########################
    ####  RELATIONSHIPS  ####
    #########################

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
    public function attachPermissions(...$permissions)
    {
        $permissions = Arr::flatten($permissions);
        foreach ($permissions as $permission) {
            $permission = $permission instanceof Permission ? $permission : Permission::whereIdOrSlug($permission, $permission)->first();

            if ($permission && !$this->permissions->contains('slug', $permission->slug)) {
                $this->permissions()->attach($permission->id);
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
    public function detachPermissions(...$permissions)
    {
        if (count($permissions)) {
            $permissions = Arr::flatten($permissions);
            foreach ($permissions as $permission) {
                $permission = $permission instanceof Permission ? $permission : Permission::whereIdOrSlug($permission, $permission)->first();

                if ($permission && $this->permissions->contains('slug', $permission->slug)) {
                    $this->permissions()->detach($permission->id);
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
            $permission = $permission instanceof Permission ? $permission : Permission::whereIdOrSlug($permission, $permission)->first();
            $sync[] = $permission->id;
        }

        if (count($sync)) {
            $this->permissions()->sync($sync);
        }

        return $this;
    }
}
