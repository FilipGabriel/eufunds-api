<?php

namespace Modules\Media\Http\Requests;

use Modules\Core\Http\Requests\Request;

class UploadMediaRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $output = [];
        parse_str(url()->previous(), $output);

        $location = $output['location'] ?? 'appfront';

        return $location !== 'downloads' ? ['image' => 'mimes:jpeg,jpg,png|max:20480'] : ['file' => 'file'];
    }
}
