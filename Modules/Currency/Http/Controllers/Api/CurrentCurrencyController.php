<?php

namespace Modules\Currency\Http\Controllers\Api;

class CurrentCurrencyController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param string $currency
     * @return \Illuminate\Http\Response
     */
    public function store($currency)
    {
        abort_if(! in_array($currency, setting('supported_currencies')), 403);
        $domain = config('app.env') == 'local' ? 'localhost' : '.euprojects.ro';

        $cookie = cookie()->forever('currency', $currency, null, $domain, true, false, false, 'none');

        return response()->json()->withCookie($cookie);
    }
}
