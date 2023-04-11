<?php

namespace Modules\Checkout\Http\Controllers\Api;

use Exception;
use Illuminate\Routing\Controller;
use Modules\Checkout\Events\OrderPlaced;
use Modules\Checkout\Services\OrderService;
use Modules\Order\Http\Requests\StoreOrderRequest;

class CheckoutController extends Controller
{
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

        event(new OrderPlaced($order));

        return response()->json([ 'orderId' => $order->id ]);
    }
}
