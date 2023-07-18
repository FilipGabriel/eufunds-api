<?php

namespace Modules\Order\Http\Requests;

use Modules\Core\Http\Requests\Request;

class StoreOrderRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name' => ['required'],
            // 'business_id' => ['required'],
            'products' => ['required'],
            'program' => ['required'],
            'terms_and_conditions' => 'accepted',
        ];
    }
}
