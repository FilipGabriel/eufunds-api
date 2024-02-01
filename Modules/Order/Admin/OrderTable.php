<?php

namespace Modules\Order\Admin;

use Modules\Admin\Ui\AdminTable;

class OrderTable extends AdminTable
{
    /**
     * Make table response for the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function make()
    {
        return $this->newTable()
            ->editColumn('funding', function ($order) {
                return optional($order->funding)->name;
            })
            ->addColumn('customer_name', function ($order) {
                return $order->customer_full_name;
            })
            ->editColumn('total', function ($order) {
                return $order->total->convert($order->currency, $order->currency_rate)->format($order->currency);
            })
            ->editColumn('type', function ($order) {
                return $order->type ? trans("program::programs.types.{$order->type}") : '';
            })
            ->setRowClass(function($order) {
                return $order->type;
            });
    }
}
