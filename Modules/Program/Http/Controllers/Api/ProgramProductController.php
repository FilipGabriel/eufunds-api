<?php

namespace Modules\Program\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Events\ProductViewed;
use Modules\Product\Filters\ProductFilter;
use Modules\Product\Http\Controllers\Api\ProductSearch;
use Modules\Product\Http\Middleware\SetProductSortOption;

class ProgramProductController extends Controller
{
    use ProductSearch;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(SetProductSortOption::class)->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $slug
     * @param \Modules\Product\Entities\Product $model
     * @param \Modules\Product\Filters\ProductFilter $productFilter
     * @return \Illuminate\Http\Response
     */
    public function index($slug, Product $model, ProductFilter $productFilter)
    {
        request()->merge(['program' => $slug ]);

        return $this->searchProducts($model, $productFilter);
    }

    /**
     * Show the specified resource.
     *
     * @param string $program
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($program, $slug)
    {
        request()->merge(['program' => $program ]);

        $product = Product::findBySlug($slug);
        $product->selling_price = $product->getSellingPrice()->amount();
        
        $relatedProducts = $product->relatedProducts()->forCard()->get();
        $upSellProducts = $product->upSellProducts()->forCard()->get();

        event(new ProductViewed($product));

        return response()->json([
            'product' => $product,
            'relatedProducts' => $this->forCard($relatedProducts),
            'upSellProducts' => $this->forCard($upSellProducts)
        ]);
    }

    private function forCard($products)
    {
        return $products->map(function($product) {
            return [
                'slug' => $product->slug,
                'name' => $product->name,
                'base_image' => $product->base_image->path ?? null,
                'description' => $product->description,
                'short_description' => $product->short_description,
                'price' => $product->getSellingPrice()->format(),
            ];
        });
    }
}
