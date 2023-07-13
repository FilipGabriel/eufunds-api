<?php

namespace Modules\Product\Http\Controllers\Api;

use Modules\Program\Entities\Program;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Http\Response\SuggestionsResponse;

class SuggestionController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $model)
    {
        $products = $this->getProducts($model);
        $searchQuery = preg_replace("/[^A-Za-z0-9]+/i", " ", request('query'));

        return new SuggestionsResponse(
            $searchQuery,
            $products,
            $products->pluck('categories')->flatten(),
            $this->getTotalResults($model)
        );
    }

    /**
     * Get total results count.
     *
     * @param \Modules\Product\Entities\Product $model
     * @return int
     */
    private function getTotalResults(Product $model)
    {
        $searchQuery = preg_replace("/[^A-Za-z0-9]+/i", " ", request('query'));

        return $model->search($searchQuery)
            ->query()
            ->when(request()->filled('program'), $this->programQuery())
            ->when(request()->filled('category'), $this->categoryQuery())
            ->count();
    }

    /**
     * Get products suggestions.
     *
     * @param \Modules\Product\Entities\Product $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getProducts(Product $model)
    {
        $searchQuery = preg_replace("/[^A-Za-z0-9]+/i", " ", request('query'));

        return $model->search($searchQuery)->query()
            ->orWhere('sku', request('query'))
            ->limit(10)->withName()->withBaseImage()->withPrice()
            ->addSelect([ 'products.id', 'products.slug', 'products.sku', 'products.qty'])
            ->with(['files', 'categories' => function ($query) {
                $query->limit(5);
            }])
            ->when(request()->filled('program'), $this->programQuery())
            ->when(request()->filled('category'), $this->categoryQuery())
            ->get();
    }

    /**
     * Returns categories condition closure.
     *
     * @return \Closure
     */
    private function categoryQuery()
    {
        return function (Builder $query) {
            $query->whereHas('categories', function ($categoryQuery) {
                $categoryQuery->where('slug', request('category'));
            });
        };
    }

    /**
     * Returns program condition closure.
     *
     * @return \Closure
     */
    private function programQuery()
    {
        return function (Builder $query) {
            $query->whereHas('programs', function ($programQuery) {
                $programQuery->where('slug', request('program'));
            });
        };

        // $program = Program::findBySlug(request('program'));
        // $categoryIds = $program->categories->pluck('id')->toArray();

        // return function (Builder $query) use ($categoryIds) {
        //     $query->whereHas('categories', function ($categoryQuery) use ($categoryIds) {
        //         $categoryQuery->whereIn('id', $categoryIds);
        //     });
        // };
    }
}
