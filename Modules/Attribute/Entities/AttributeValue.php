<?php

namespace Modules\Attribute\Entities;

use Modules\Support\Eloquent\Model;

class AttributeValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'position', 'value'];
}
