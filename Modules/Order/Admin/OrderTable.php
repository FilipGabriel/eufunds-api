<?php

namespace Modules\Order\Admin;

use Modules\Admin\Ui\AdminTable;

class OrderTable extends AdminTable
{
    /**
     * Raw columns that will not be escaped.
     *
     * @var array
     */
    protected $rawColumns = ['company_name'];

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
            ->editColumn('company_name', function ($order) {
                $companyName = $order->company_name;

                return $order->partner ? "{$companyName}</br>Partener: {$order->partner}" : $companyName;
            })
            ->editColumn('total', function ($order) {
                return $order->total->convert($order->currency, $order->currency_rate)->format($order->currency);
            })
            ->editColumn('type', function ($order) {
                return $order->type ? trans("program::programs.types.{$order->type}") : '';
            });
    }
}
