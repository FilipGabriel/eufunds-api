<?php

namespace Modules\Checkout;

use JsonSerializable;
use Modules\Product\Entities\Product;
use Modules\Support\Money;

class CartItem implements JsonSerializable
{
    public $qty;
    public $product;
    public $options;

    public function __construct($item)
    {
        $this->qty = $item['qty'];
        $this->product = Product::find($item['id']);
        $this->options = collect($item['options'] ?? []);
    }

    public function unitPrice()
    {
        return $this->product->getSellingPrice()->add($this->optionsPrice());
    }

    public function total()
    {
        return $this->unitPrice()->multiply($this->qty);
    }

    public function optionsPrice()
    {
        return Money::inDefaultCurrency($this->calculateOptionsPrice());
    }

    public function calculateOptionsPrice()
    {
        return $this->options->sum(function ($option) {
            return $this->valuesSum($option->values);
        });
    }

    private function valuesSum($values)
    {
        return $values->sum(function ($value) {
            if ($value->price_type === 'fixed') {
                return $value->price->amount();
            }

            return ($value->price / 100) * $this->product->getSellingPrice()->amount();
        });
    }

    public function jsonSerialize()
    {
        return [
            'qty' => $this->qty,
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
