<?php

namespace Modules\User\Http\Requests;

use Modules\Core\Http\Requests\Request;

class RegisterRequest extends Request
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
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6'],
            'captcha' => ['required', 'captcha'],
            'privacy_policy' => ['accepted'],
        ];
    }
}
