<?php

namespace Modules\Support\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Support\Services\SettlementService;


class SettlementController
{
    private SettlementService $settlementService;

    public function __construct(SettlementService $settlementService)
    {
        $this->settlementService = $settlementService;
    }

    public function searchCity(Request $request)
    {
        $response = [];
        if ($request->has('term')) {
            $response = $this->settlementService->search($request->get('term'), true);
        }

        return response()->json($response);
    }

    public function searchSettlement(Request $request)
    {
        $response = [];
        if ($request->has('term')) {
            $response = $this->settlementService->search($request->get('term'));
        }

        return response()->json($response);
    }
}
