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

    private $products = [];

    public function handle()
    {
        $this->getProducts();

        foreach($this->products as $product) {
            $this->createProduct($product);
        }
    }

    private function getProducts($page = 1)
    {
        try {
            $response = $this->getRequest("/products?page={$page}");
            $this->products = array_merge($this->products, $response->result->products);
        } catch (Exception $e) {
            Log::info("Page {$page}: {$e->getMessage()}");
            return;
        }

        if($response->result->total_pages > $page) {
            self::getProducts($page+1);
        }
    }

    private function createProduct($product)
    {
        $values = [
            'name' => $product->title,
            'brand_id' => Brand::whereNodId($product->manufacturer_id)->first()->id ?? null,
            'warranty' => $product->warranty,
            'price' => $product->promo_price,
            'short_description' => $product->description,
            'description' => $product->long_description,
            'sku' => $product->code,
            'is_active' => true,
        ];

        $productCategoryId = $product->product_category_id;
        $newProduct = Product::updateOrCreate(['nod_id' => $product->id], $values);
        $categoryIds = Category::getNestCategoryBy($productCategoryId, [$productCategoryId]);

        $newProduct->categories()->sync($categoryIds);
        $this->handleImages($product->pictures, $newProduct);
    }

    private function handleImages($images, $product)
    {
        foreach($images as $key => $image) {
            $disk = config('filesystems.default');
            $location = $key == 0 ? 'base_image' : 'additional_images';
            $url = $image->url_overlay_picture;
            $name = substr($url, strrpos($url, '/') + 1);
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
