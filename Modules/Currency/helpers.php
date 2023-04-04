<?php

use Modules\Support\Money;

if (! function_exists('format_discount')) {
    /**
     * Get the integer representation value of the permission.
     *
     * @param array $amount
     * @param string $currency
     * @return int
     */
    function format_discount($amount)
    {
        return Money::inDefaultCurrency($amount);
    }
}