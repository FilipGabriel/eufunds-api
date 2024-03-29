<div class="dashboard-panel">
    <div class="grid-header">
        <h4><i class="fa fa-shopping-cart" aria-hidden="true"></i>{{ trans('admin::dashboard.latest_orders') }}</h4>
    </div>

    <div class="clearfix"></div>

    <div class="table-responsive anchor-table">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ trans('admin::dashboard.table.latest_orders.order_id') }}</th>
                    <th></th>
                    <th>{{ trans('admin::dashboard.table.latest_orders.company_name') }}</th>
                    <th>{{ trans('admin::dashboard.table.customer') }}</th>
                    <th>{{ trans('admin::dashboard.table.latest_orders.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestOrders as $latestOrder)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $latestOrder) }}">
                                {{ $latestOrder->id }}
                            </a>
                        </td>
                        <td></td>
                        <td>
                            <a href="{{ route('admin.orders.show', $latestOrder) }}">
                                {{ $latestOrder->company_name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $latestOrder) }}">
                                {{ $latestOrder->customer_full_name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $latestOrder) }}">
                                {{ $latestOrder->total->convert($latestOrder->currency, $latestOrder->currency_rate)->format($latestOrder->currency) }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="empty" colspan="5">{{ trans('admin::dashboard.no_data') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
