<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
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
            'categories' => $this->getProgramCategories(),
            'program' => Program::findBySlug(request('program'))
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
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'variants' => $product->options->count(),
                    'short_description' => $product->short_description,
                    'base_image' => $product->base_image->path ?? null,
                    'selling_price' => $product->getSellingPrice()
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
            })->orderBy('name')->distinct('name')->get()->unique('name')->flatten();
    }

    private function getProgramCategories()
    {
        $program = Program::findBySlug(request('program'));
        $categoryIds = $program->categories->pluck('id')->merge(
            $program->categories->pluck('parent_id')
        )->toArray();

        return Category::treeIds($categoryIds);
    }

    private function getProductsCategoryIds($productIds)
    {
        return DB::table('product_categories')
            ->whereIn('product_id', $productIds)
            ->distinct()
            ->pluck('category_id');
    }
}
