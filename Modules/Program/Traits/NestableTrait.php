<?php

namespace Modules\Program\Traits;

use Modules\Category\NestableCollection;

trait NestableTrait
{
    /**
     * Return a custom nested collection.
     *
     * @return NestableCollection
     */
    public function newCollection(array $models = [])
    {
        return new NestableCollection($models);
    }
}
