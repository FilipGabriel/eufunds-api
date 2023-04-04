<?php

if (! function_exists('product_price_formatted')) {
    /**
     * Get the selling price of the given product.
     *
     * @param \Modules\Product\Entities\Product $product
     * @param \Closure $callback
     * @return string
     */
    function product_price_formatted($product, $callback = null)
    {
        $price = $product->price->convertToCurrentCurrency()->format();
        $specialPrice = $product->getSpecialPrice()->convertToCurrentCurrency()->format();

        if (is_callable($callback)) {
            return $callback($price, $specialPrice);
        }

        if (! $product->hasSpecialPrice()) {
            return $price;
        }

        return "{$specialPrice} <span class='previous-price'>{$price}</span>";
    }
}
