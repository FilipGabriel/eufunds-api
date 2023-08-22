<div class="order-information-wrapper">
    <div class="order-information-buttons">
        <a href="{{ route('admin.orders.print.show', $order) }}" class="btn btn-default" target="_blank" data-toggle="tooltip" title="{{ trans('order::orders.print') }}">
            <i class="fa fa-print" aria-hidden="true"></i>
        </a>

        <form method="POST" action="{{ route('admin.orders.email.store', $order) }}">
            {{ csrf_field() }}

            <button type="submit" class="btn btn-default" data-toggle="tooltip" title="{{ trans('order::orders.send_email') }}" data-loading>
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
            </button>
        </form>
    </div>

    <h3 class="section-title">{{ trans('order::orders.order_and_account_information') }}</h3>

    <div class="row">
        <div class="col-md-6">
            <div class="order clearfix">
                <h4>{{ trans('order::orders.order_information') }}</h4>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>{{ trans('order::orders.order_date') }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            
                            <tr>
                                <td>{{ trans('order::orders.table.funding') }}</td>
                                <td>{{ $order->funding->name }}</td>
                            </tr>

                            @if (currency() != $order->currency)
                                <tr>
                                    <td>{{ trans('order::orders.currency') }}</td>
                                    <td>{{ $order->currency }}</td>
                                </tr>

                                <tr>
                                    <td>{{ trans('order::orders.currency_rate') }}</td>
                                    <td>{{ $order->currency_rate }}</td>
                                </tr>
                            @endif

                            @if ($order->note)
                                <tr>
                                    <td>{{ trans('order::orders.order_note') }}</td>
                                    <td>{{ $order->note }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="account-information">
                <h4>{{ trans('order::orders.account_information') }}</h4>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>{{ trans('order::orders.company_name') }}</td>
                                @if($order->business_id)
                                <td>{{ $order->business_id }} - {{ $order->company_name }}</td>
                                @else
                                <td>{{ $order->company_name }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>{{ trans('order::orders.customer_name') }}</td>
                                <td>{{ $order->customer_full_name }}</td>
                            </tr>

                            <tr>
                                <td>{{ trans('order::orders.customer_email') }}</td>
                                <td>{{ $order->customer_email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
