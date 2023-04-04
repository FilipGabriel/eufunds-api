<?php

namespace Modules\Support\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Support\Agency;
use Modules\Support\State;

class AgencyController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $agencies = collect(Agency::all())->map(function($agency) {
            $agency['supported_states'] = implode(', ', collect($agency['supported_states'])
                ->map(function($code) {
                    return State::name('RO', $code);
                })->toArray());

            return $agency;
        });

        return response()->json($agencies);
    }
}
