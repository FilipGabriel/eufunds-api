<?php

namespace Modules\Checkout\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Modules\Product\Entities\Product;
use Modules\Program\Entities\Program;

class CheckCartItems
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $program = Program::findBySlug(request('program'));
        $categoryIds = $program->categories->pluck('id')->toArray();

        try {
            foreach($request->products as $cartItem) {
                $this->checkCategoriesAndPrice($cartItem, $categoryIds);
            }
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], 422);
        }

        return $next($request);
    }

    private function checkCategoriesAndPrice($cartItem, $categoryIds)
    {
        $product = Product::find($cartItem['id']);

        if ($cartItem['selling_price']['amount'] != $product->getSellingPrice()->amount()) {
            throw new Exception(trans('checkout::messages.product_has_changed_price', ['product' => $product->name]));
        }

        if (! array_intersect($product->categories->pluck('id')->toArray(), $categoryIds)) {
            throw new Exception(trans('checkout::messages.product_unavailable', ['product' => $product->name]));
        }
    }
}
