<?php

namespace Themes\Appfront\Http\Requests;

use Modules\Core\Http\Requests\Request;

class SaveAppfrontRequest extends Request
{
    /**
     * Array of attributes that should be merged with null
     * if attribute is not found in the current request.
     *
     * @var array
     */
    private $shouldCheck = [
        
    ];

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        foreach ($this->shouldCheck as $attribute) {
            if (! $this->has($attribute)) {
                $this->merge([$attribute => null]);
            }
        }

        return $this->all();
    }
}
