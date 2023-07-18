<?php

namespace Modules\Order\Http\Controllers\Api;

use PhpOffice\PhpWord\Settings;
use Modules\Support\TemplateProcessor;

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

    // /**
    //  * Download Order
    //  *
    //  * @param int $id
    //  */
    // public function download(int $id)
    // {
    //     $order = auth()->user()->orders()->with(['products'])
    //         ->where('id', $id)
    //         ->firstOrFail();

    //     $path = storage_path("offers") . "/Oferta {$order->company_name}.pdf";

    //     Pdf::loadView('emails.invoice', [
    //         'download' => true,
    //         'order' => $order,
    //         'logo' => File::findOrNew(setting('appfront_mail_logo'))->path,
    //     ])->setPaper('a4', 'landscape')->save($path);

    //     return response()->download($path, "Oferta {$order->company_name}.pdf", ['Content-Type' => 'pdf']);
    //     // return $file->download("Oferta {$order->company_name}.pdf");
    // }

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

        $class = 'Modules\\Order\\Exports\\Offer';
        $document = new $class();
        
        $params = $document->getData($order);
        $path = public_path("templates/order.docx");
        // Settings::setOutputEscapingEnabled(true);
        $template = new TemplateProcessor($path);
        $template->setValues($params);

        if(method_exists($document, 'getExtraRules')) {
            $template = $document->getExtraRules($order, $template);
        }

        $name = trim($order->company_name);
        $fileName = "Oferta - {$name}";
        $file = $this->saveFile($fileName, $template);

        return response()->download($file, "{$fileName}.doc", ['Content-Type' => 'application/msword']);
    }

    /**
     * @param string $file
     * @param string $html
     * @return string
     */
    private function saveFile(string $fileName, TemplateProcessor $template): string
    {
        if (! is_dir(storage_path("offers"))) {
            mkdir(storage_path("offers"));
        }

        $path = storage_path("offers");
        $file = "{$path}/{$fileName}.doc";
        $template->saveAs($file);

        return $file;
    }
}
