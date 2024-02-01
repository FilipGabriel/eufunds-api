<?php

namespace Modules\Checkout;

use JsonSerializable;
use Modules\Support\Money;
use Modules\Option\Entities\Option;
use Modules\Product\Entities\Product;

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
        if(request()->has('presales')) {
            return $this->product->getSellingPrice()->add($this->optionsPrice());
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
            $valueSubmitted = request()->has('presales') ? $option['values'][0]['value'] ?? null : null;
            $option = Option::find($option['id']);
            
            return $this->valuesSum($option->values, $valueSubmitted);
        });
    }

    private function valuesSum($values, $valueSubmitted)
    {
        return $values->sum(function ($value) use ($valueSubmitted) {
            if ($value->price_type === 'fixed') {
                return $value->price->amount();
            }

            if(request()->has('presales')) {
                return ($valueSubmitted / 100) * $this->product->getSellingPrice()->amount();
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
