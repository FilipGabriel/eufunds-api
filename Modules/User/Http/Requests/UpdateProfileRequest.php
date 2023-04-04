<?php

namespace Modules\User\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\Request;

class UpdateProfileRequest extends Request
{
    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = 'user::attributes.users';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'experience' => 'nullable|array',
            'education' => 'nullable|array',
            'skills' => 'nullable|array',
            'languages' => 'nullable|array',
            'certificates' => 'nullable|array',
            'driving_license' => 'nullable|array',
            'volunteering' => 'nullable|array'
        ];
    }
}
