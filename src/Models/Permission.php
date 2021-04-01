<?php

namespace LuizHenriqueFerreira\LaravelAcl\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Permission
 *
 * @package LuizHenriqueFerreira\LaravelAcl\Models
 */
class Permission extends Model
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
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
