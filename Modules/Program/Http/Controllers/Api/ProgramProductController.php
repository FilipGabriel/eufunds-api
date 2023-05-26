<?php

namespace Modules\Program\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Program\Entities\Program;
use Modules\Option\Entities\OptionValue;
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
        $product = Product::findBySlug($slug);
        $program = Program::findBySlug($program);

        abort_if(
            ! array_intersect($program->categories->pluck('id')->toArray(), $product->categories->pluck('id')->toArray()
        ), 404);

        $product->stock = $product->qty;
        $product->qty = 1;
        $product->has_dnsh = $product->has_dnsh;
        $product->brand_name = $product->brand->name;
        $product->selling_price = $product->getSellingPrice()->amount();
        $product->manage_stock = $product->manage_stock && in_array('acquisition', $program->types ?? ['acquisition']);
        $product->variants = $product->options->map(function($option) use ($product) {
            return [
                'id' => $option->id,
                'type' => $option->type,
                'values' => $option->values->map(function (OptionValue $value) use ($product) {
                    return [
                        'id' => $value->id,
                        'label' => $value->label . $value->formattedPriceForProduct($product),
                        'amount' => (float) $value->priceForProduct($product)->convertToCurrentCurrency()->amount(),
                    ];
                })
            ];
        });
        
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
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'base_image' => $product->base_image->path ?? null,
                'selling_price' => $product->getSellingPrice(),
            ];
        });
    }
}
