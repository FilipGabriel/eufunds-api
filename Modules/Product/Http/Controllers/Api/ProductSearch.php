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
        $searchQuery = preg_replace("/[^A-Za-z0-9]+/i", " ", request('query'));

        if (request()->filled('query')) {
            $model = $model->search($searchQuery);
            $productIds = $model->keys();
        }

        $query = $model->filter($productFilter);

        if (request()->filled('category')) {
            $productIds = (clone $query)->select('products.id')->resetOrders()->pluck('id');
        }

        $products = $query->paginate(request('perPage', 30));

        event(new ShowingProductList($products));

        $program = Program::findBySlug(request('program'));

        return response()->json([
            'products' => $this->transform($products, $program->types),
            'attributes' => $this->getAttributes($productIds),
            'categories' => $this->getProgramCategories(),
            'program' => $program,
            'brands' => $this->getBrandsByCategory()
        ]);
    }

    /**
     * Transform the products.
     *
     * @return \Illuminate\Support\Collection
     */
    private function transform($products, $programTypes = ['acquisition'])
    {
        return $products->setCollection(
            $products->getCollection()->map(function($product) use ($programTypes) {
                return [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'qty' => 1,
                    'stock' => $product->qty,
                    'has_dnsh' => $product->has_dnsh,
                    'in_stock' => $product->isInStock(),
                    'variants' => $product->options->count(),
                    'short_description' => $product->short_description,
                    'base_image' => $product->base_image->path ?? null,
                    'selling_price' => $product->getSellingPrice(),
                    'end_special_price' => $product->special_price_end ? $product->special_price_end->format('d.m.Y') : null,
                    'manage_stock' => $product->manage_stock && in_array('acquisition', $programTypes)
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

    private function getBrandsByCategory()
    {
        if (! request()->filled('category')) {
            return collect();
        }

        return Product::whereHas('programs', function ($programQuery) {
            $programQuery->where('slug', request('program'));
        })->whereHas('categories', function ($categoryQuery) {
            $categoryQuery->where('slug', request('category'));
        })->get()->map(function ($product) {
            return [
                'slug' => $product->brand->slug,
                'name' => $product->brand->name
            ];
        })->unique()->toArray();
    }

    private function getProductsCategoryIds($productIds)
    {
        $category = Category::findBySlug(request('category'));
        $categories = Category::whereParentId($category->id)->get();

        if($categories->isEmpty()) {
            return [$category->id];
        }

        $categoryIds = DB::table('product_categories')
            ->whereIn('product_id', $productIds)
            ->distinct()
            ->pluck('category_id')
            ->toArray();

        return array_values(array_intersect($categories->pluck('id')->toArray(), $categoryIds));
    }
}
