<?php

namespace Modules\User\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\Request;

class SaveUserRequest extends Request
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
            'name' => 'required',
            'about' => 'nullable|max:1000',
            'username' => ['sometimes', Rule::unique('users')->ignore($this->id, 'id')],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->id, 'id')],
            'phone' => ['required'],
            'image' =>'nullable|image',
            'password' => 'nullable|confirmed|min:6',
            'roles' => ['sometimes', Rule::exists('roles', 'id')],
            'profile_public' => 'sometimes|boolean',
            'phone_public' => 'sometimes|boolean',
            'email_public' => 'sometimes|boolean'
        ];
    }
}
