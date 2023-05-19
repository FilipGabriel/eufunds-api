<?php

namespace Modules\Brand\Http\Controllers\Api;

use Modules\Brand\Entities\Brand;
use Modules\Product\Entities\Product;
use Modules\Product\Filters\ProductFilter;
use Modules\Product\Http\Controllers\Api\ProductSearch;

class BrandProductController
{
    use ProductSearch;

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
        request()->merge(['brand' => $slug]);

        $query = $model->filter($productFilter);

        $brand = Brand::findBySlug($slug);
        $products = $query->paginate(request('perPage', 30));

        return response()->json([
            'brandName' => $brand->name,
            'brandBanner' => $brand->banner->path,
            'products' => $this->transform($products),
        ]);
    }
}
