<?php

namespace LuizHenriqueFerreira\LaravelAcl\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait HasSlug
 *
 * @package LuizHenriqueFerreira\LaravelAcl\Models\Traits
 */
trait HasSlug
{
    /**
     * Make Slug a field.
     *
     * @return void
     */
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            if (!isset($model->attributes['slug']) || empty($model->attributes['slug'])) {
                $model->attributes['slug'] = Str::slug($model->attributes['title']);
            }
        });

        static::updating(function (Model $model) {
            if (!isset($model->attributes['slug']) || empty($model->attributes['slug'])) {
                $model->attributes['slug'] = Str::slug($model->attributes['title']);
            }
        });
    }
}
