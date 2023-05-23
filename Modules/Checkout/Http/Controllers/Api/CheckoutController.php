<?php

namespace Modules\Checkout\Http\Controllers\Api;

use Exception;
use Illuminate\Routing\Controller;
use Modules\Checkout\Events\OrderPlaced;
use Modules\Checkout\Services\OrderService;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Checkout\Http\Middleware\CheckCartItems;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckCartItems::class)->only(['store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Order\Http\Requests\StoreOrderRequest $request
     * @param \Modules\Checkout\Services\OrderService $orderService
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        $order = $orderService->create($request);
        
        try {
            $orderService->storeOrderProducts($request, $order);
        } catch (Exception $e) {
            $orderService->delete($order);

            return response()->json([ 'message' => $e->getMessage() ], 422);
        }

        event(new OrderPlaced($order, $request->type));

        return response()->json([ 'orderId' => $order->id ]);
    }
}
