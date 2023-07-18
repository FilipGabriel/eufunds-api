<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
	</head>

	<body style="font-family: 'Open Sans', sans-serif;
		font-size: 10px;
		min-width: 320px;
		color: #555555;
		margin: 0;
        max-width: 1170px;
        margin-right: auto;
        margin-left: auto;"
		>
		<table style="width: 100%; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<td colspan="10" style="height: 30pt; border-bottom: solid black 1px; border-top: solid black 1px; border-left: solid black 1px; border-right: solid black 1px;">
                        <p style="text-align: center;">
                            <em><span style="font-size: 20.0pt; color: black;">Factura Proforma</span></em>
                        </p>
                    </td>
				</tr>
				<tr style="height: 21.0pt;">
					<td colspan="3" style="border: solid black 1.0pt; border-right: solid black 1.0pt; height: 21.0pt;">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Program</span></strong>
                        </p>
					</td>
					<td colspan="3" style="border: 1.0pt solid black;height: 21.0pt;">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Beneficiar</span></strong>
                        </p>
					</td>
					<td colspan="2" style="border: solid black 1.0pt; border-left: none; height: 21.0pt;">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Tip oferta</span></strong>
                        </p>
					</td>
					<td colspan="1" style="border: solid black 1.0pt; border-left: none; height: 21.0pt;">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Valabilitate oferta</span></strong>
                        </p>
					</td>
					<td colspan="1" style="border-top: solid black 1.0pt; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt; height: 21.0pt;">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Termen de livrare</span></strong>
                        </p>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="padding: 10px; border-top: none; border-left: solid black 1.0pt; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt;">
						<p style="text-align: center;" align="center">
                            <span style="color: black;">{{ $order->funding->name }}</span>
                        </p>
					</td>
					<td colspan="3" style="padding: 10px; border-top: none; border-left: solid black 1.0pt; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt;">
						<p style="text-align: center;" align="center">
							@if($order->business_id)
                            <span style="color: black;">{{ $order->business_id }} - {{ $order->company_name }}</span>
							@else
                            <span style="color: black;">{{ $order->company_name }}</span>
							@endif
						</p>
					</td>
					<td colspan="2" style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt;">
						<p style="text-align: center;" align="center">
                            <span style="color: black;">{{ trans("program::programs.types.{$order->type}") }}</span>
						</p>
					</td>
					<td colspan="1" style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt;">
						<p style="text-align: center;" align="center">
                            <span style="color: black;">30 zile</span>
						</p>
					</td>
					<td colspan="1" style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt;">
						<p style="text-align: center;" align="center">
                            <span style="color: black;">60-90 zile</span>
                        </p>
					</td>
				</tr>
				<tr style="height: 30.0pt;">
					<td style="height: 15.0pt; border-bottom: solid black 1px; border-left: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px; border-right: solid black 1px;"></td>
				</tr>
				<tr style="height: 15.0pt;">
					<td style="padding: 10px; border: solid black 1.0pt; height: 15.0pt;" colspan="1">
						<p style="text-align: center;" align="center">
							<strong><span style="color: black;">Nr crt</span></strong>
						</p>
					</td>
					<td style="padding: 10px; border: solid black 1.0pt; border-left: none; height: 15.0pt;" colspan="1">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Cod produs</span></strong>
						</p>
					</td>
					<td style="padding: 10px; border: solid black 1.0pt; border-left: none; height: 15.0pt;" colspan="3">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Denumirea produselor sau a serviciilor</span></strong>
						</p>
					</td>
					<td style="padding: 10px; border: solid black 1.0pt; border-left: none; height: 15.0pt;" colspan="1">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">UM</span></strong>
                        </p>
					</td>
					<td style="padding: 10px; border: solid black 1.0pt; border-left: none; height: 15.0pt;" colspan="1">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Cantitate</span></strong>
						</p>
					</td>
					<td style="padding: 10px; border: solid black 1.0pt; border-left: none; height: 15.0pt;" colspan="1">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Pret unitar</span></strong>
						</p>
					</td>
					<td style="padding: 10px; border: solid black 1.0pt; border-left: none; height: 15.0pt;" colspan="1">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Valoare</span></strong>
						</p>
					</td>
					<td style="padding: 10px; border: solid black 1.0pt; border-left: none; height: 15.0pt;" colspan="1">
						<p style="text-align: center;" align="center">
                            <strong><span style="color: black;">Garantie</span></strong>
						</p>
					</td>
				</tr>
				@foreach ($order->products as $key => $product)
				<tr style="height: 30.0pt;">
					<td style="padding: 10px; border: solid black 1.0pt; border-top: none; height: 30.0pt;" colspan="1">
						<p style="text-align: center;" align="center">
							<span style="color: black;">{{ $key+1 }}</span>
						</p>
					</td>
					<td style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt; background: white; height: 30.0pt;"
						colspan="1">
						<p style="text-align: center;" align="center">
							<span style="color: black;">{{ $product->product->sku ?: '-' }}</span>
						</p>
					</td>
					<td style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt; background: white; height: 30.0pt;"
						colspan="3">
						<p style="text-align: center;" align="center">
							<span style="color: black;">
							{!! $product->product->name !!}
							</span>
							@if ($product->hasAnyOption())
							@foreach ($product->options as $option)
							<br /><span style="color: #9a9a9a;">
							@if ($option->option->isFieldType())
							{{ $option->value }}
							@else
							{{ $option->values->implode('label', ', ') }}
							@endif
							</span>
							@endforeach
							@endif
						</p>
					</td>
					<td style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt; height: 30.0pt;"
						colspan="1">
						<p style="text-align: center;" align="center">
                            <span style="color: black;">buc</span>
						</p>
					</td>
					<td style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt; height: 30.0pt;"
						colspan="1">
						<p style="text-align: center;" align="center">
                            <span style="color: black;">{{ $product->qty }}</span>
                        </p>
					</td>
					<td style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt; height: 30.0pt;"
						colspan="1">
						<p style="text-align: center;" align="center">
                            <span style="color: black;">{{ $product->unit_price->format('RON') }}</span>
						</p>
					</td>
					<td style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt; height: 30.0pt;"
						colspan="1">
						<p style="text-align: center;" align="center">
                            <span style="color: black;">{{ $product->line_total->format('RON') }}</span>
						</p>
					</td>
					<td style="padding: 10px; border-top: none; border-left: none; border-bottom: solid black 1.0pt; border-right: solid black 1.0pt; height: 30.0pt;"
						colspan="1">
						<p style="text-align: center;" align="center">
                            <span style="color: black;">{{ $product->product->warranty ?: '-' }}</span>
                        </p>
					</td>
				</tr>
				@endforeach
				<tr style="height: 30.0pt;">
					<td style="height: 15.0pt; border-bottom: solid black 1px; border-left: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px;"></td>
					<td style="height: 15.0pt; border-bottom: solid black 1px; border-right: solid black 1px; padding-right: 10px;"
						colspan="2">
						<p style="text-align: right; color: black">Total:
							<strong>{{ $order->total->format('RON') }}</strong>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>