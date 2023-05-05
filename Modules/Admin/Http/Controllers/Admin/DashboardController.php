<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Modules\User\Entities\User;
use Modules\Order\Entities\Order;
use Illuminate\Contracts\View\View;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\SearchTerm;
use Illuminate\Support\Facades\Http;

class DashboardController
{
    /**
     * Display the dashboard with its widgets.
     *
     */
    public function index()
    {
        $response = Http::withHeaders(['Nod-User' => '4eb6946e6e9155be282882202207f4f2'])
            ->post('https://api-eufunds.smis.ro/api/v1/tokens', [
                'nod_id' => '123456',
                'name' => 'Client Nod',
                'email' => 'client_nod_email@smis.ro',
                'manager_name' => 'Manager',
                'manager_email' => 'manager@smis.ro',
            ])->json();

        return redirect()->to("https://eufunds.smis.ro?token={$response['token']}");

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
