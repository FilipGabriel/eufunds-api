<?php

namespace Smis\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Modules\Brand\Entities\Brand;
use Modules\Support\Traits\NodApi;
use Illuminate\Support\Facades\Log;
use Modules\Product\Entities\Product;
use Modules\Category\Entities\Category;

class ImportProductsCommand extends Command
{
    use NodApi;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nod:import-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import NOD products';

    public function handle()
    {
        try {
            $response = $this->getRequest('/products');
            $products = $response->result->products;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        foreach($products as $product) {
            $this->createProduct($product);
        }
    }

    private function createProduct($product)
    {
        $values = [
            'name' => $product->title,
            'brand_id' => Brand::whereNodId($product->manufacturer_id)->first()->id ?? null,
            'slug' => str_slug($product->name),
            'warranty' => $product->warranty,
            'price' => $product->promo_price,
            'short_description' => $product->description,
            'description' => $product->long_description,
            'sku' => $product->code,
            'is_active' => true,
        ];

        if(Product::whereNodId($product->id)->first()) {
            unset($values['slug']);
        }

        $newProduct = Product::updateOrCreate(['nod_id' => $product->id], $values);
        $categoryIds = Category::getNestCategoryBy($product->product_category_id, [$product->product_category_id]);

        $newProduct->categories()->sync($categoryIds);
    }
}
