<?php

namespace Smis\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Modules\Support\Traits\NodApi;
use Illuminate\Support\Facades\Log;
use Modules\Product\Entities\Product;

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
                $response = $this->getRequest("/products/{$product->nod_id}");
                $this->updateOrCreateProduct($response->product);
            } catch (Exception $e) {
                Log::info("Get product info {$product->nod_id}: {$e->getMessage()}");
            }
        });
    }

    private function updateOrCreateProduct($product)
    {
        Product::withoutGlobalScope('active')->updateOrCreate(['nod_id' => $product->id], [
            'price' => $product->ron_promo_price,
            'qty' => $product->stock_value,
            'in_stock' => $product->stock_value > 0,
            'special_price_end' => $product->special_price_valid_to,
            'documents' => collect($product->documents)->map(function($doc) {
                return [
                    'name' => $doc->document_name,
                    'path' => $doc->document_data
                ];
            })->toArray()
        ]);
    }
}
