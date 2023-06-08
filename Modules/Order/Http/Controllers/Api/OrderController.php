<?php

namespace Modules\Order\Http\Controllers\Api;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Media\Entities\File;

class OrderController
{
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
                    'type' => $order->type,
                    'business_id' => $order->business_id,
                    'company_name' => $order->company_name,
                    'total' => $order->total->format('RON'),
                    'program' => $order->funding->name,
                    'created' => $order->created_at->format('d M Y'),
                    'offer' => route('account.orders.download', $order->id),
                ];
            });

        return response()->json($orders);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = auth()->user()->orders()->with(['products'])
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'id' => $order->id,
            'type' => $order->type,
            'business_id' => $order->business_id,
            'company_name' => $order->company_name,
            'total' => $order->total->format(),
            'program' => [
                'slug' => $order->program,
                'name' => $order->funding->name
            ],
            'products' => $order->products->map(function($product) {
                return [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'base_image' => $product->product->base_image->path ?? null,
                    'qty' => $product->qty,
                    'unit_price' => $product->unit_price->format(),
                    'total' => $product->line_total->format(),
                    'variants' => $product->options->map(function($option) {
                        return $option->values->implode('label', ', ');
                    })
                ];
            }),
            'created' => $order->created_at->format('d M Y'),
        ]);
    }

    /**
     * Download Order
     *
     * @param int $id
     */
    public function download(int $id)
    {
        $order = auth()->user()->orders()->with(['products'])
            ->where('id', $id)
            ->firstOrFail();

        $path = storage_path("offers") . "/Oferta {$order->company_name}.pdf";

        Pdf::loadView('emails.invoice', [
            'download' => true,
            'order' => $order,
            'logo' => File::findOrNew(setting('appfront_mail_logo'))->path,
        ])->setPaper('a4', 'landscape')->save($path);

        return response()->download($path, "Oferta {$order->company_name}.pdf", ['Content-Type' => 'pdf']);
        // return $file->download("Oferta {$order->company_name}.pdf");
    }
}
