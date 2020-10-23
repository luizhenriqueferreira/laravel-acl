<?php

namespace LuizHenriqueBK\LaravelAcl\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * @package LuizHenriqueBK\LaravelAcl\Models
 */
class Permission extends Model
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
     * A permission can be applied to roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
