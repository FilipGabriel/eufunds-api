<?php

namespace Modules\Admin\Http\Controllers\Api;

use Modules\User\Entities\User;

class DashboardController
{
    public function index()
    {
        return response()->json([
            'users' =>  User::count()
        ]);
    }
}
