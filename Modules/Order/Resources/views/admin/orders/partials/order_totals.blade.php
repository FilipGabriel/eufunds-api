<div class="order-totals-wrapper">
    <div class="row">
        <div class="order-totals pull-right">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>{{ trans('order::orders.total') }}</td>
                            <td class="text-right">{{ $order->total->format() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
