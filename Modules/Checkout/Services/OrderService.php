<?php

namespace Modules\Checkout\Services;

use Modules\Support\Money;
use Modules\Checkout\CartItem;
use Modules\Order\Entities\Order;
use Modules\Currency\Entities\CurrencyRate;

class OrderService
{
    public function create($request)
    {
        $currency = currency();

        return Order::create([
            'customer_id' => auth()->id(),
            'program' => $request->program,
            'type' => $request->type,
            'company_name' => $request->company_name,
            'business_id' => $request->business_id,
            'customer_email' => auth()->user()->email,
            'customer_phone' => auth()->user()->phone ?? null,
            'customer_first_name' => auth()->user()->name ?? null,
            'customer_last_name' => null,
            'sub_total' => 0,
            'total' => 0,
            'payment_method' => 'bank_transfer',
            'currency' => $currency,
            'currency_rate' => CurrencyRate::for($currency),
            'locale' => locale(),
            'status' => Order::PENDING_PAYMENT,
        ]);
    }

    public function storeOrderProducts($request, Order $order)
    {
        $total = Money::inDefaultCurrency(0);

        foreach($request->products as $item) {
            $cartItem = new CartItem($item);
            $total = $total->add($cartItem->total());

            $order->storeProducts($cartItem);
        }

        $order->update([
            'sub_total' => $total->amount(),
            'total' => $total->amount()
        ]);
    }

    private function storeOrderDownloads($request, Order $order)
    {
        foreach($request->products as $item) {
            $order->storeDownloads(new CartItem($item));
        }
    }

    public function delete(Order $order)
    {
        $order->delete();
    }
}
