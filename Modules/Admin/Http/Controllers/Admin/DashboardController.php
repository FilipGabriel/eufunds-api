<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Modules\User\Entities\User;
use Modules\Order\Entities\Order;
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
            'totalSales' => Order::totalSales(),
            'totalCustomers' => User::totalCustomers(),
            'latestSearchTerms' => $this->getLatestSearchTerms(),
            'totalProducts' => Product::withoutGlobalScope('active')->count(),
            'latestOrders' => $this->getLatestOrders(),
            'totalOrders' => Order::withoutCanceledOrders()->count(),
        ]);
    }

    private function getLatestSearchTerms()
    {
        return SearchTerm::latest('updated_at')->take(5)->get();
    }

    /**
     * Get latest five orders.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getLatestOrders()
    {
        return Order::select([
            'id',
            'business_id',
            'company_name',
            'customer_first_name',
            'total',
            'created_at',
        ])->latest()->take(5)->get();
    }
}
