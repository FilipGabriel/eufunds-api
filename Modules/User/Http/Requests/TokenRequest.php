<?php

namespace Modules\User\Http\Requests;

use Modules\Core\Http\Requests\SimpleErrorRequest;

class TokenRequest extends SimpleErrorRequest
{
    /**
     * Available attributes for users.
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
            'nod_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'manager_email' => 'required|email',
            'manager_name' => 'required'
        ];
    }
}
