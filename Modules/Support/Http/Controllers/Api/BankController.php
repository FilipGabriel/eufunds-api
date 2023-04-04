<?php

namespace Modules\Support\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Support\Bank;

use function GuzzleHttp\Promise\all;

class BankController extends Controller
{
    public function index()
    {
        return Bank::all();
    }


}
