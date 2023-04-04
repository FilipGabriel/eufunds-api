<?php

namespace Modules\Program\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Program\Entities\Program;
use Modules\Product\Entities\Product;
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
}
