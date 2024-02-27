<?php

namespace Modules\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Rap2hpoutre\FastExcel\FastExcel;
use Modules\Admin\Traits\HasCrudActions;

class OrderController
{
    use HasCrudActions;

    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['products', 'coupon'];

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'order::orders.order';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'order::admin.orders';

    public function export(Request $request)
    {
        return (new FastExcel($this->rowGenerator($request)))->download('Comenzi.xlsx');
    }

    public function exportProducts(Request $request)
    {
        return (new FastExcel($this->rowProductGenerator($request)))->download('Produse Comenzi.xlsx');
    }

    private function rowGenerator($request)
    {
        $rows = Order::latest();

        foreach ($rows->cursor() as $row) {
            yield collect([
                'ID' => $row->id,
                'Program' => $row->funding->name,
                'Tip' => $row->type ? trans("program::programs.types.{$row->type}") : '',
                'CUI' => $row->business_id,
                'Beneficiar' => $row->company_name,
                'Client' => $row->customer_first_name,
                'Email' => $row->customer_email,
                'Total' => $row->total->round()->amount(),
                'Data comanda' => $row->created_at->format('d.m.Y / H:i'),
            ]);
        };
    }

    private function rowProductGenerator($request)
    {
        $rows = Order::with('products')->latest();

        foreach ($rows->cursor() as $row) {
            foreach($row->products as $product) {
                yield collect([
                    'ID Comanda' => $row->id,
                    'Program' => $row->funding->name,
                    'Beneficiar' => $row->company_name,
                    'Tip' => $row->type ? trans("program::programs.types.{$row->type}") : '',
                    'Cod produs' => $product->product->sku,
                    'Nume produs' => $product->name,
                    'Brand' => $product->product->brand->name,
                    'Cantitate' => $product->qty,
                    'Pret Unitar' => $product->unit_price->round()->amount(),
                    'Total' => $product->line_total->round()->amount(),
                    'Data comanda' => $row->created_at->format('d.m.Y / H:i'),
                ]);
            }
        };
    }
}
