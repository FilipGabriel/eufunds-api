<?php

namespace Smis\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Modules\Media\Entities\File;
use Modules\Brand\Entities\Brand;
use Modules\Support\Traits\NodApi;
use Illuminate\Support\Facades\Log;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Storage;
use Modules\Category\Entities\Category;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

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
        $products = [];

        try {
            $response = $this->getRequest("/products/full-feed");

            $products = $response->products;
        } catch (Exception $e) {
            Log::info("Get products: {$e->getMessage()}");
            return;
        }

        foreach($products as $product) {
            try {
                $this->updateOrCreateProduct($product);
            } catch (Exception $e) {
                Log::info("Product {$product->id}: {$e->getMessage()}");
            }
        }
    }

    private function updateOrCreateProduct($product)
    {
        $newProduct = Product::updateOrCreate(['nod_id' => $product->id], [
            'name' => $product->title,
            'brand_id' => Brand::whereNodId($product->manufacturer_id)->first()->id ?? null,
            'warranty' => $product->warranty,
            'price' => $product->ron_promo_price,
            'short_description' => $product->description,
            'description' => $product->long_description,
            'qty' => $product->stock_value,
            'sku' => $product->code,
            'manage_stock' => true,
            'in_stock' => $product->stock_value > 0
        ]);

        $productCategoryId = $product->product_category_id;
        if(! $newProduct->categories->pluck('id')->contains($productCategoryId)) {
            $categoryIds = Category::getNestCategoryBy($productCategoryId, [$productCategoryId]);
    
            $newProduct->categories()->sync($categoryIds);
        }
        
        $this->handleImages($product->images, $newProduct);
    }

    private function handleImages($images, $product)
    {
        foreach(explode(',', $images) as $key => $url) {
            $disk = config('filesystems.default');
            $location = $key == 0 ? 'base_image' : 'additional_images';
            $name = substr($url, strrpos($url, '/') + 1);

            if($url && ! $product->files()->whereFilename($name)->exists()) {
                $path = "media/{$location}/{$name}";
                Storage::disk($disk)->put($path, file_get_contents($url));
                $file = new SymfonyFile(public_path("storage/{$path}"));

                $newFile = File::create([
                    'user_id' => 1,
                    'disk' => 'public_storage',
                    'location' => $location,
                    'filename' => $name,
                    'path' => $path,
                    'extension' => $file->guessExtension() ?? '',
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);

                $product->files()->attach($newFile, ['zone' => $location, 'entity_type' => get_class($product)]);
            }
        }
    }
}
