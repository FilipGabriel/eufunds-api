<!DOCTYPE html>
<html lang="{{ locale() }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ trans('order::print.estimate') }}</title>

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
        <link href="{{ v(Module::asset('order:admin/css/print.css')) }}" rel="stylesheet">
    </head>

    <body class="{{ is_rtl() ? 'rtl' : 'ltr' }}">
        <!--[if lt IE 8]>
            <p>You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="container">
            <div class="invoice-wrapper clearfix">
                <div class="row">
                    <div class="invoice-header clearfix">
                        <div class="col-md-3">
                            <div class="store-name">
                                <h1>{{ setting('store_name') }}</h1>
                            </div>
                        </div>

                        <div class="col-md-9 clearfix">
                            <div class="invoice-header-right pull-right">
                                <span class="title">{{ trans('order::print.estimate') }}</span>

                                <div class="invoice-info clearfix">
                                    <div class="invoice-id">
                                        <label for="invoice-id">{{ trans('order::print.invoice_id') }}:</label>
                                        <span>#{{ $order->id }}</span>
                                    </div>

                                    <div class="invoice-date">
                                        <label for="invoice-date">{{ trans('order::print.date') }}:</label>
                                        <span>{{ $order->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="invoice-body clearfix">
                    <div class="invoice-details-wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="invoice-details">
                                    <h5>{{ trans('order::print.order_details') }}</h5>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>{{ trans('order::orders.table.funding') }}</td>
                                                    <td>{{ $order->funding->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ trans('order::orders.business_id') }}</td>
                                                    <td>{{ $order->business_id }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ trans('order::orders.company_name') }}</td>
                                                    <td>{{ $order->company_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ trans('order::print.email') }}:</td>
                                                    <td>{{ $order->customer_email }}</td>
                                                </tr>

                                                <tr>
                                                    <td>{{ trans('order::print.phone') }}:</td>
                                                    <td>{{ $order->customer_phone }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="cart-list">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('order::print.key') }}</th>
                                            <th>{{ trans('order::print.sku') }}</th>
                                            <th>{{ trans('order::print.product') }}</th>
                                            <th>{{ trans('order::print.unit_price') }}</th>
                                            <th>{{ trans('order::print.quantity') }}</th>
                                            <th>{{ trans('order::print.line_total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->products as $key => $product)
                                            <tr>
                                                <td>
                                                    <label class="visible-xs">{{ trans('order::print.key') }}:</label>
                                                    <span>{{ ++$key }}</span>
                                                </td>

                                                <td>
                                                    <label class="visible-xs">{{ trans('order::print.sku') }}:</label>
                                                    <span>{{ $product->product->sku }}</span>
                                                </td>

                                                <td>
                                                    <span>{{ $product->name }}</span>

                                                    @if ($product->hasAnyOption())
                                                        <div class="option">
                                                            @foreach ($product->options as $option)
                                                                <span>
                                                                    {{ $option->name }}:

                                                                    <span>
                                                                        @if ($option->option->isFieldType())
                                                                            {{ $option->value }}
                                                                        @else
                                                                            {{ $option->values->implode('label', ', ') }}
                                                                        @endif
                                                                    </span>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>

                                                <td>
                                                    <label class="visible-xs">{{ trans('order::print.unit_price') }}:</label>
                                                    <span>{{ $product->unit_price->convert($order->currency, $order->currency_rate)->convert($order->currency, $order->currency_rate)->format($order->currency) }}</span>
                                                </td>

                                                <td>
                                                    <label class="visible-xs">{{ trans('order::print.quantity') }}:</label>
                                                    <span>{{ $product->qty }}</span>
                                                </td>
                                                <td>
                                                    <label class="visible-xs">{{ trans('order::print.line_total') }}:</label>
                                                    <span>{{ $product->line_total->convert($order->currency, $order->currency_rate)->format($order->currency) }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="total pull-right">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('order::print.subtotal') }}</td>
                                        <td>{{ $order->sub_total->convert($order->currency, $order->currency_rate)->format($order->currency) }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ trans('order::print.total') }}</td>
                                        <td>{{ $order->total->convert($order->currency, $order->currency_rate)->format($order->currency) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <script>
            window.print();
        </script> -->
    </body>
</html>
