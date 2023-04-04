<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Modules\User\Entities\User;
use Illuminate\Contracts\View\View;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\SearchTerm;

class DashboardController
{
    /**
     * Display the dashboard with its widgets.
     *
     * @return view
     */
    public function index() :view
    {
        return view('admin::dashboard.index', [
            'totalCustomers' => User::totalCustomers(),
            'latestSearchTerms' => $this->getLatestSearchTerms(),
            'totalProducts' => Product::withoutGlobalScope('active')->count(),
        ]);
    }

    private function getLatestSearchTerms()
    {
        return SearchTerm::latest('updated_at')->take(5)->get();
    }
}
