<?php

namespace Modules\User\Http\Requests;

use Modules\Core\Http\Requests\SimpleErrorRequest;

class SaveUserDeviceRequest extends SimpleErrorRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'device_type' => ['nullable', 'sometimes'],
            'resolution' => ['nullable', 'sometimes'],
            'os' => ['nullable', 'sometimes'],
            'os_version' => ['nullable', 'sometimes'],
            'browser' => ['nullable', 'sometimes'],
            'browser_version' => ['nullable', 'sometimes'],
            'mobile_vendor' => ['nullable', 'sometimes'],
            'mobile_model' => ['nullable', 'sometimes'],
        ];
    }
}
