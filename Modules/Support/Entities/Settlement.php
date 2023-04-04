<?php

namespace Modules\Support\Entities;

use Modules\Support\Eloquent\Model;

class Settlement extends Model
{
    protected $fillable = ['county_code', 'county', 'name', 'village', 'full_name'];
}
