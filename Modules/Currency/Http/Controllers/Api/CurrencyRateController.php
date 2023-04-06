<?php

namespace Modules\Currency\Http\Controllers\Api;

use Modules\Currency\Entities\CurrencyRate;

class CurrencyRateController
{
    /**
     * Display currencies in json.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = CurrencyRate::get()->mapWithKeys(function($value) {
            return [
                $value->currency => (float) $value->rate
            ];
        });

        return response()->json($currencies);
    }
}
