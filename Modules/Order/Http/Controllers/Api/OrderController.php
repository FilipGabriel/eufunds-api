<?php

namespace Modules\Order\Http\Controllers\Api;

use Exception;
use PhpOffice\PhpWord\Settings;
use Modules\Program\Entities\Program;
use PhpOffice\PhpWord\TemplateProcessor;
use Modules\Checkout\Events\OrderPlaced;

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
                    'total' => $order->total->convert($order->currency, $order->currency_rate)->format($order->currency),
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
            ->findOrFail($id);

        return response()->json([
            'id' => $order->id,
            'type' => $order->type,
            'business_id' => $order->business_id,
            'company_name' => $order->company_name,
            'total' => $order->total->convert($order->currency, $order->currency_rate)->format($order->currency),
            'program' => [
                'slug' => $order->program,
                'name' => $order->funding->name
            ],
            'products' => $order->products->map(function($product) use ($order) {
                return [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'base_image' => $product->product->base_image->path ?? null,
                    'qty' => $product->qty,
                    'unit_price' => $product->unit_price->convert($order->currency, $order->currency_rate)->format($order->currency),
                    'total' => $product->line_total->convert($order->currency, $order->currency_rate)->format($order->currency),
                    'variants' => $product->options->map(function($option) {
                        return $option->values->implode('label', ', ');
                    })
                ];
            }),
            'created' => $order->created_at->format('d M Y'),
        ]);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function transform($id)
    {
        $order = auth()->user()->orders()->with(['products'])
            ->findOrFail($id);

        abort_if($order->type == 'acquisition', 403);

        $program = Program::findBySlug($order->program);
        $categoryIds = $program->categories->pluck('id')->toArray();

        try {
            foreach($order->products as $cartItem) {
                $this->checkCategoriesAndPrice($cartItem, $categoryIds);
                $this->checkQuantity($cartItem);
            }
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], 422);
        }

        $order->type = 'acquisition';
        $order->save();

        event(new OrderPlaced($order, 'acquisition'));

        return response()->json([ 'message' => 'Comanda ta a fost plasata cu succes!' ], 200);
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
            ->findOrFail($id);

        $class = 'Modules\\Order\\Exports\\Offer';
        $document = new $class();
        
        $params = $document->getData($order);
        $path = public_path("templates/order.docx");
        Settings::setOutputEscapingEnabled(true);
        $template = new TemplateProcessor($path);
        $template->setValues($params);

        if(method_exists($document, 'getExtraRules')) {
            $template = $document->getExtraRules($order, $template);
        }

        $name = preg_replace("/[^A-Za-z0-9\.\-\_]+/i", " ", trim($order->company_name));
        $fileName = "Oferta - {$name}";
        $file = $this->saveFile($fileName, $template);

        return response()->download($file, "{$fileName}.doc");
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

    private function checkCategoriesAndPrice($cartItem, $categoryIds)
    {
        if (! $cartItem->product || ! array_intersect($cartItem->product->categories->pluck('id')->toArray(), $categoryIds)) {
            throw new Exception(trans('checkout::messages.product_unavailable', ['product' => $cartItem->product->name]));
        }

        if ((float) $cartItem->unit_price->amount() != $cartItem->product->getSellingPrice()->amount()) {
            throw new Exception(trans('checkout::messages.product_has_changed_price', ['product' => $cartItem->product->name]));
        }
    }

    private function checkQuantity($cartItem)
    {
        if ($cartItem->product->isOutOfStock()) {
            throw new Exception(trans('checkout::messages.product_is_out_of_stock', ['product' => $cartItem->product->name]));
        }

        if (($cartItem->product->qty - $cartItem->qty) < 0) {
            throw new Exception(trans('checkout::messages.product_doesn\'t_have_enough_stock', ['product' => $cartItem->product->name]));
        }
    }
}
