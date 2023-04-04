<?php

namespace Modules\Support\Eloquent;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booting()
    {
        static::saved(function ($entity) {
            $entity->clearEntityTaggedCache();
        });

        static::deleted(function ($entity) {
            $entity->clearEntityTaggedCache();
        });
    }

    public static function queryWithoutEagerRelations()
    {
        return (new static)->newQueryWithoutEagerRelations();
    }

    public function newQueryWithoutEagerRelations()
    {
        return $this->registerGlobalScopes(
            $this->newModelQuery()->withCount($this->withCount)
        );
    }

    public function clearEntityTaggedCache()
    {
        Cache::tags($this->getTable())->flush();
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && (is_null($value) || empty($value))) {
            return [];
        }

        return parent::castAttribute($key, $value);
    }

    /**
     * Register a new active global scope on the model.
     *
     * @return void
     */
    public static function addActiveGlobalScope()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('is_active', true);
        });
    }

    /**
     * Register a new approved global scope on the model.
     *
     * @return void
     */
    public static function addApprovedGlobalScope()
    {
        static::addGlobalScope('approved', function ($query) {
            $query->where('approved', true);
        });
    }

    /**
     * Register a new opened global scope on the model.
     *
     * @return void
     */
    public static function addOpenedGlobalScope()
    {
        static::addGlobalScope('opened', function ($query) {
            $query->where('opened', true);
        });
    }
}
