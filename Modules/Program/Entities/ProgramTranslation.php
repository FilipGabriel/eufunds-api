<?php

namespace Modules\Program\Entities;

use Modules\Support\Eloquent\TranslationModel;

class ProgramTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'title'];
}
