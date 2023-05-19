<?php

namespace Modules\Brand\Http\Controllers\Api;

use Modules\Brand\Entities\Brand;

class BrandController
{
    /**
     * Display brands.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'activeBrands' => Brand::searchable()
        ]);
    }
}
