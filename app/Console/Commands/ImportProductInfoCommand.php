<?php

namespace Smis\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Modules\Support\Traits\NodApi;
use Illuminate\Support\Facades\Log;
use Modules\Product\Entities\Product;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImportProductInfoCommand extends Command
{
    use NodApi;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nod:import-product-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import NOD product info';

    public function handle()
    {
        Product::whereNotNull('nod_id')->get()->map(function($product) {
            try {
                $response = $this->getRequest("/products/{$product->nod_id}?show_extended_info=1");
                $this->updateOrCreateProduct($response->product, $product->options ?? []);
            } catch (Exception $e) {
                Log::info("Get product info {$product->nod_id}: {$e->getMessage()}");

                if($e instanceof NotFoundHttpException) {
                    Log::info("Not found");
                }
            }
        });
    }

    private function updateOrCreateProduct($product, $options)
    {
        Product::withoutGlobalScope('active')->updateOrCreate(['nod_id' => $product->id], [
            'price' => $product->ron_promo_price,
            'qty' => $product->stock_value,
            'in_stock' => $product->stock_value > 0,
            'special_price_valid_to' => $product->special_price_valid_to,
            'supplier_stock' => $product->supplier_stock_value,
            'supplier_stock_date' => $product->supplier_stock_delivery_date,
            'reserved_stock' => $product->reserved_stock_value,
            'is_on_demand_only' => $product->is_on_demand_only,
            'documents' => collect($product->documents)->map(function($doc) {
                return [
                    'name' => $doc->document_name,
                    'path' => $doc->document_data
                ];
            })->toArray(),
            'options' => $options
        ]);
    }
}
