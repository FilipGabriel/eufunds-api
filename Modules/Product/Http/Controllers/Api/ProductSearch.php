<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Modules\Coupon\Entities\Coupon;
use Modules\Program\Entities\Program;
use Modules\Product\Entities\Product;
use Modules\Category\Entities\Category;
use Modules\Attribute\Entities\Attribute;
use Modules\Product\Filters\ProductFilter;
use Modules\Product\Events\ShowingProductList;

trait ProductSearch
{
    /**
     * Search products for the request.
     *
     * @param \Modules\Product\Entities\Product $model
     * @param \Modules\Product\Filters\ProductFilter $productFilter
     * @return \Illuminate\Http\Response
     */
    public function searchProducts(Product $model, ProductFilter $productFilter)
    {
        $productIds = [];

        if (request()->filled('query')) {
            $model = $model->search(request('query'));
            $productIds = $model->keys();
        }

        $query = $model->filter($productFilter);

        if (request()->filled('category')) {
            $productIds = (clone $query)->select('products.id')->resetOrders()->pluck('id');
        }

        $products = $query->paginate(request('perPage', 30));

        event(new ShowingProductList($products));

        return response()->json([
            'products' => $this->transform($products),
            'attributes' => $this->getAttributes($productIds),
            'categories' => $this->getProgramCategories()
        ]);
    }

    /**
     * Transform the products.
     *
     * @return \Illuminate\Support\Collection
     */
    private function transform($products)
    {
        return $products->setCollection(
            $products->getCollection()->map(function($product) {
                return [
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'base_image' => $product->base_image->path ?? null,
                    'description' => $product->description,
                    'short_description' => $product->short_description,
                    'price' => $this->applyDiscounts($product)->format(),
                ];
            })
        );
    }

    private function getAttributes($productIds)
    {
        if (! request()->filled('category')) {
            return collect();
        }

        return Attribute::with('values')
            ->where('is_filterable', true)
            ->whereHas('categories', function ($query) use ($productIds) {
                $query->whereIn('id', $this->getProductsCategoryIds($productIds));
            })
            ->get();
    }

    private function getProgramCategories()
    {
        $program = Program::findBySlug(request('program'));
        $categoryIds = $program->categories->pluck('id')->toArray();

        return Category::treeIds($categoryIds);
    }

    private function getProductsCategoryIds($productIds)
    {
        return DB::table('product_categories')
            ->whereIn('product_id', $productIds)
            ->distinct()
            ->pluck('category_id');
    }

    private function applyDiscounts($product)
    {
        $sellingPrice = $product->getSellingPrice();

        $sellingPrice = $this->applyProgramDiscounts($product, $sellingPrice);
        $sellingPrice = $this->applyCategoryDiscounts($product, $sellingPrice);
        $sellingPrice = $this->applyUserDiscount($product, $sellingPrice);

        return $sellingPrice;
    }

    private function applyProgramDiscounts($product, $sellingPrice)
    {
        $program = Program::findBySlug(request('program'));

        foreach($product->getCouponsByProgram($program->id) as $couponId) {
            $coupon = Coupon::find($couponId);

            if(
                $coupon && $coupon->valid() && ! $coupon->usageLimitReached() &&
                ! $coupon->perCustomerUsageLimitReached() && ! $coupon->excludePrograms->contains($program->id)
            ) {
                $sellingPrice = $sellingPrice->subtract($coupon->getCalculatedValue($product->price));
            }
        }

        return $sellingPrice;
    }

    private function applyCategoryDiscounts($product, $sellingPrice)
    {
        $categoryIds = $product->categories->pluck('id');
        
        foreach($product->getCouponsByCategory($categoryIds) as $couponId) {
            $coupon = Coupon::find($couponId);

            if(
                $coupon && $coupon->valid() && ! $coupon->usageLimitReached() && ! $coupon->perCustomerUsageLimitReached() &&
                $coupon->excludeCategories->intersect($product->categories)->isEmpty()
            ) {
                $sellingPrice = $sellingPrice->subtract($coupon->getCalculatedValue($product->price));
            }
        }

        return $sellingPrice;
    }

    private function applyUserDiscount($product, $sellingPrice)
    {
        foreach($product->getCouponsByUser() as $couponId) {
            $coupon = Coupon::find($couponId);

            if(
                $coupon && $coupon->valid() && ! $coupon->usageLimitReached() &&
                ! $coupon->perCustomerUsageLimitReached() && ! $coupon->excludeUsers->contains(auth()->id())
            ) {
                $sellingPrice = $sellingPrice->subtract($coupon->getCalculatedValue($product->price));
            }
        }

        return $sellingPrice;
    }
}
