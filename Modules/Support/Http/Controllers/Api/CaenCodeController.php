<?php

namespace Modules\Support\Http\Controllers\Api;


use Illuminate\Routing\Controller;
use Modules\Support\Caen;

class CaenCodeController extends Controller
{
    public function index()
    {
        return response()->json(Caen::all());
    }
}
