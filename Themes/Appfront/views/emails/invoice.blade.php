<!DOCTYPE html>
<html lang="en" style="-ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%;
                    -webkit-print-color-adjust: exact;"
>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">

        <style>
            td {
                vertical-align: top;
            }

            @media screen and (max-width: 767px) {
                .order-details {
                    width: 100% !important;
                }

                .shipping-address {
                    width: 100% !important;
                }

                .billing-address {
                    width: 100% !important;
                }
            }
        </style>
    </head>

    <body style="font-family: 'Open Sans', sans-serif;
                font-size: 15px;
                min-width: 320px;
                color: #555555;
                margin: 0;"
    >
        <table style="border-collapse: collapse;
                    min-width: 320px;
                    max-width: 900px;
                    width: 100%;
                    margin: auto;
                    border-bottom: 2px solid {{ mail_theme_color() }};"
        >
            <tbody>
                <tr>
                    <td style="padding: 0;">
                        <table style="border-collapse: collapse;
                                    width: 100%;
                                    background: {{ mail_theme_color() }};"
                        >
                            <tbody>
                                <tr>
                                    <td style="padding: 0 15px; text-align: center;">
                                        @if (is_null($logo))
                                            <h1 style="font-family: 'Open Sans', sans-serif;
                                                    font-weight: 400;
                                                    font-size: 32px;
                                                    line-height: 39px;
                                                    display: inline-block;
                                                    color: #444444;
                                                    margin: 17px 0 0;"
                                            >
                                                {{ setting('store_name') }}
                                            </h1>
                                        @else
                                            <div style="display: flex;
                                                        height: 64px;
                                                        width: 200px;
                                                        align-items: center;
                                                        justify-content: center;
                                                        margin: auto;"
                                            >
                                                <img src="{{ $logo }}" style="max-height: 100%; max-width: 100%;" alt="logo">
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0 15px; text-align: center;">
                                        <span style="font-family: 'Open Sans', sans-serif;
                                                    font-weight: 400;
                                                    font-size: 56px;
                                                    line-height: 68px;
                                                    display: inline-block;
                                                    color: #444444;
                                                    margin: 3px 0 5px;"
                                        >
                                            {{ trans('appfront::invoice.invoice') }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0 15px;">
                                        <table style="border-collapse: collapse;
                                                    width: 230px;
                                                    margin: 0 auto 20px;"
                                        >
                                            <tbody>
                                                <tr>
                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                            font-size: 16px;
                                                            font-weight: 400;
                                                            color: #444444;
                                                            padding: 0;"
                                                    >
                                                        <span style="float: left;">
                                                            {{ trans('appfront::invoice.order_id') }}:
                                                        </span>

                                                        <span style="float: right;">
                                                            #{{ $order->id }}
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                            font-size: 16px;
                                                            font-weight: 400;
                                                            color: #444444;
                                                            padding: 0;"
                                                    >
                                                        <span style="float: left;">
                                                            {{ trans('appfront::invoice.date') }}:
                                                        </span>

                                                        <span style="float: right;">
                                                            {{ $order->created_at->format('d M Y') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 30px 15px;">
                        <table style="border-collapse: collapse;
                                    min-width: 320px;
                                    max-width: 760px;
                                    width: 100%;
                                    margin: auto;"
                        >
                            <tbody>
                                <tr>
                                    <td style="padding: 0;">
                                        <table style="border-collapse: collapse; width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="padding: 0;">
                                                        <h5 style="font-family: 'Open Sans', sans-serif;
                                                                font-weight: 600;
                                                                font-size: 18px;
                                                                line-height: 22px;
                                                                margin: 0 0 8px;
                                                                color: #444444;"
                                                        >
                                                            {{ trans('appfront::invoice.order_details') }}
                                                        </h5>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="padding: 0;">
                                                        <table class="order-details" style="border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                                            font-weight: 400;
                                                                            font-size: 15px;
                                                                            padding: 4px 4px 4px 0;"
                                                                    >
                                                                        {{ trans('appfront::invoice.funding') }}:
                                                                    </td>

                                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                                            font-weight: 400;
                                                                            font-size: 15px;
                                                                            padding: 4px 4px 4px 0;
                                                                            word-break: break-all;"
                                                                    >
                                                                        {{ $order->funding->name }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                                            font-weight: 400;
                                                                            font-size: 15px;
                                                                            padding: 4px 4px 4px 0;"
                                                                    >
                                                                        {{ trans('appfront::invoice.company_name') }}:
                                                                    </td>

                                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                                            font-weight: 400;
                                                                            font-size: 15px;
                                                                            padding: 4px 4px 4px 0;
                                                                            word-break: break-all;"
                                                                    >
                                                                        {{ $order->business_id }} - {{ $order->company_name }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                                            font-weight: 400;
                                                                            font-size: 15px;
                                                                            padding: 4px 4px 4px 0;"
                                                                    >
                                                                        {{ trans('appfront::invoice.email') }}:
                                                                    </td>

                                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                                            font-weight: 400;
                                                                            font-size: 15px;
                                                                            padding: 4px 4px 4px 0;
                                                                            word-break: break-all;"
                                                                    >
                                                                        {{ $order->customer_email }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                                            font-weight: 400;
                                                                            font-size: 15px;
                                                                            padding: 4px 4px 4px 0;"
                                                                    >
                                                                        {{ trans('appfront::invoice.phone') }}:
                                                                    </td>

                                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                                            font-weight: 400;
                                                                            font-size: 15px;
                                                                            padding: 4px 4px 4px 0;
                                                                            word-break: break-all;"
                                                                    >
                                                                        {{ $order->customer_phone }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 30px 0 0;">
                                        <table style="border-collapse: collapse;
                                                    width: 100%;
                                                    border-bottom: 1px solid #e9e9e9;"
                                        >
                                            <tbody>
                                                @foreach ($order->products as $product)
                                                    <tr style="border-top: 1px solid #f1f1f1;">
                                                        <td style="padding: 14px 0 14px;">
                                                            <table style="border-collapse: collapse;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="padding: 0 0 8px;">
                                                                            <span
                                                                                style="font-family: 'Open Sans', sans-serif;
                                                                                    font-weight: 400;
                                                                                    font-size: 18px;
                                                                                    line-height: 22px;
                                                                                    color: #444444;
                                                                                    margin: 0;
                                                                                    text-align: justify;"
                                                                            >
                                                                                {!! $product->product->description !!}
                                                                            </span>
                                                                        </td>
                                                                    </tr>

                                                                    @if ($product->hasAnyOption())
                                                                        <tr>
                                                                            <td style="padding: 0;">
                                                                                <table style="border-collapse: collapse; width: 100%;">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td style="font-family: 'Open Sans', sans-serif;
                                                                                                    font-weight: 400;
                                                                                                    font-size: 14px;
                                                                                                    padding: 0 0 8px;"
                                                                                            >
                                                                                                @foreach ($product->options as $option)
                                                                                                    <span style="color: #9a9a9a;">
                                                                                                        @if ($option->option->isFieldType())
                                                                                                            {{ $option->value }}
                                                                                                        @else
                                                                                                            {{ $option->values->implode('label', ', ') }}
                                                                                                        @endif
                                                                                                    </span>
                                                                                                @endforeach
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    <tr>
                                                                        <td style="font-family: 'Open Sans', sans-serif;
                                                                                font-weight: 400;
                                                                                font-size: 16px;
                                                                                padding: 0 0 4px;"
                                                                        >
                                                                            <span>
                                                                                {{ trans('appfront::invoice.unit_price') }}:
                                                                            </span>

                                                                            <span style="margin-left: 5px;">
                                                                                {{ $product->unit_price->convert($order->currency, $order->currency_rate)->format($order->currency) }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td style="font-family: 'Open Sans', sans-serif;
                                                                                font-weight: 400;
                                                                                font-size: 16px;
                                                                                padding: 0 0 4px;"
                                                                        >
                                                                            <span>
                                                                                {{ trans('appfront::invoice.quantity') }}:
                                                                            </span>

                                                                            <span style="margin-left: 5px;">
                                                                                {{ $product->qty }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td style="font-family: 'Open Sans', sans-serif;
                                                                                font-weight: 400;
                                                                                font-size: 16px;
                                                                                padding: 0 0 4px;"
                                                                        >
                                                                            <span>
                                                                                {{ trans('appfront::invoice.line_total') }}:
                                                                            </span>

                                                                            <span style="margin-left: 5px;">
                                                                                {{ $product->line_total->convert($order->currency, $order->currency_rate)->format($order->currency) }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0;">
                                        <table style="border-collapse: collapse;
                                                    width: 300px;
                                                    margin-top: 10px;
                                                    float: right;"
                                        >
                                            <tbody>
                                                <tr>
                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                            font-size: 17px;
                                                            font-weight: 600;
                                                            padding: 5px 0;"
                                                    >
                                                        {{ trans('appfront::invoice.total') }}
                                                    </td>

                                                    <td style="font-family: 'Open Sans', sans-serif;
                                                            font-size: 17px;
                                                            font-weight: 600;
                                                            padding: 5px 0;
                                                            float: right;"
                                                    >
                                                        {{ $order->total->convert($order->currency, $order->currency_rate)->format($order->currency) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
