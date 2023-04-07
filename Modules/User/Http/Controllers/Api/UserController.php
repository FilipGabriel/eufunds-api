<?php

namespace Modules\User\Http\Controllers\Api;

use Modules\User\Entities\User;
use Modules\User\Transformers\UserTransformer;

class UserController
{
    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'user::users.user';

    /**
     * Get an instance of the currently authenticated user
     */
    public function getAuthenticated()
    {
        auth()->user()->update([ 'last_login' => now() ]);

        return new UserTransformer(auth()->user());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'business_id' => $order->business_id,
                    'company_name' => $order->company_name,
                    'total' => $order->total->format(),
                ];
            });

        return response()->json($orders);
    }
}
