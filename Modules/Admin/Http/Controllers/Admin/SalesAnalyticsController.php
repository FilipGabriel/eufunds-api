<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Carbon\Carbon;
use Modules\Order\Entities\Order;
use Illuminate\Support\Collection;

class SalesAnalyticsController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Modules\Order\Entities\Order $order
     * @return \Illuminate\Http\Response
     */
    public function index(Order $order)
    {
        return response()->json([
            'labels' => $this->getDayNames(),
            'data' => $order->salesAnalytics(),
        ]);
    }

    private function getDayNames()
    {
        Carbon::setlocale(locale());

        return Collection::times(7)->map(function($idx) {
            $today = now();

            return ucfirst($today->addDays($idx)->translatedFormat('l'));
        })->toArray();
    }
}
