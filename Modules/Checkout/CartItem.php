<?php

namespace Modules\Checkout;

use JsonSerializable;
use Modules\Support\Money;
use Modules\Option\Entities\Option;
use Modules\Product\Entities\Product;

class CartItem implements JsonSerializable
{
    public $qty;
    public $psPrice;
    public $product;
    public $options;

    public function __construct($item)
    {
        $this->qty = $item['qty'];
        $this->psPrice = $item['ps_price'] ?? null;
        $this->product = Product::find($item['id']);
        $this->options = collect($item['options'] ?? []);
    }

    public function unitPrice()
    {
        if(request()->has('presales')) {
            return Money::inDefaultCurrency($this->psPrice['amount'] ?? 0);
        }

        return $this->product->getSellingPrice();
    }

    public function total()
    {
        if(request()->has('presales')) {
            return $this->unitPrice()->multiply($this->qty);
        }

        return $this->unitPrice()->multiply($this->qty)->add($this->optionsPrice());
    }

    public function optionsPrice()
    {
        return Money::inDefaultCurrency($this->calculateOptionsPrice());
    }

    public function calculateOptionsPrice()
    {
        return $this->options->sum(function ($option) {
            $option = Option::find($option['id']);
            
            return $this->valuesSum($option->values);
        });
    }

    private function valuesSum($values)
    {
        return $values->sum(function ($value) {
            if ($value->price_type === 'fixed') {
                return $value->price->amount();
            }

            if(request()->has('presales')) {
                return ($value->price / 100) * $this->psPrice['amount'];
            }

            return ($value->price / 100) * $this->product->getSellingPrice()->amount();
        });
    }

    public function jsonSerialize()
    {
        return [
            'qty' => $this->qty,
            'ps_price' => $this->psPrice,
            'product' => $this->product->clean(),
            'options' => $this->options->keyBy('position'),
            'unitPrice' => $this->unitPrice(),
            'total' => $this->total(),
        ];
    }

    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }
}
