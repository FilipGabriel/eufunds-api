<?php

namespace Modules\Product\Http\Controllers\Api;

use Modules\Product\Entities\Product;
use Modules\Product\Events\ProductViewed;

class ProductController
{
    /**
     * Show the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::findBySlug($slug);
        $relatedProducts = $product->relatedProducts()->forCard()->get();
        $upSellProducts = $product->upSellProducts()->forCard()->get();

        event(new ProductViewed($product));

        return response()->json([
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'upSellProducts' => $upSellProducts
        ]);
    }
}
