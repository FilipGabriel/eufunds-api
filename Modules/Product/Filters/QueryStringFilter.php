<?php

namespace Modules\Product\Filters;

use Illuminate\Support\Facades\DB;
use Modules\Program\Entities\Program;
use Illuminate\Database\Query\JoinClause;
use Modules\Attribute\Entities\Attribute;
use Modules\Attribute\Entities\AttributeValue;

class QueryStringFilter
{
    private $sorts = [
        'relevance',
        'alphabetic',
        'toprated',
        'latest',
        'pricelowtohigh',
        'pricehightolow',
    ];

    private $groupColumns = [
        'products.id',
        'slug',
        'price',
        'selling_price',
        'special_price',
        'special_price_type',
        'special_price_start',
        'special_price_end',
        'special_price_valid_to',
        'in_stock',
        'manage_stock',
        'qty',
        'new_from',
        'new_to',
    ];

    public function sort($query, $sortType)
    {
        if ($this->sortTypeExists($sortType)) {
            return $this->{$sortType}($query);
        }
    }

    private function sortTypeExists($sortType)
    {
        return in_array(strtolower($sortType), $this->sorts);
    }

    public function relevance()
    {
        // Products are searched by relevant order by default.
    }

    public function alphabetic($query)
    {
        $query->join('product_translations', function (JoinClause $join) {
            $join->on('products.id', '=', 'product_translations.product_id');
        })
        ->groupBy(array_merge($this->groupColumns, ['product_translations.name']))
        ->orderBy('product_translations.name');
    }

    public function topRated($query)
    {
        $query->selectRaw('AVG(reviews.rating) as avg_rating')
            ->leftJoin('reviews', function (JoinClause $join) {
                $join->on('products.id', '=', 'reviews.product_id');
                $join->on('reviews.is_approved', '=', DB::raw('1'));
            })
            ->groupBy($this->groupColumns)
            ->orderByDesc('avg_rating');
    }

    public function latest($query)
    {
        $query->latest();
    }

    public function priceLowToHigh($query)
    {
        $query->orderBy('selling_price');
    }

    public function priceHighToLow($query)
    {
        $query->orderByDesc('selling_price');
    }

    public function brand($query, $slug)
    {
        $query->whereHas('brand', function ($brandQuery) use ($slug) {
            $brandQuery->where('slug', $slug);
        });
    }

    public function stock($query)
    {
        $query->whereInStock(true);
    }

    public function category($query, $slug)
    {
        $query->whereHas('categories', function ($categoryQuery) use ($slug) {
            $categoryQuery->where('slug', $slug);
        });
    }

    public function program($query, $slug)
    {
        $query->whereHas('programs', function ($programQuery) use ($slug) {
            $programQuery->where('slug', $slug);
        });

        // $program = Program::findBySlug($slug);
        // $categoryIds = $program->categories->pluck('id')->toArray();

        // $query->whereHas('categories', function ($categoryQuery) use ($categoryIds) {
        //     $categoryQuery->whereIn('id', $categoryIds);
        // });
    }

    public function attribute($query, $attributeFilters)
    {
        foreach ($this->getAttributeIds($attributeFilters) as $index => $attributeId) {
            $query->join("product_attributes as pa_{$index}", 'products.id', '=', "pa_{$index}.product_id")
                ->whereRaw("pa_{$index}.attribute_id = {$attributeId} AND EXISTS (
                    SELECT *
                    FROM `product_attribute_values`
                    WHERE `pa_{$index}`.`id` = `product_attribute_values`.`product_attribute_id`
                    AND `attribute_value_id` in ({$this->getAttributeValueIds($attributeFilters)})
                )");
        }
    }

    private function getAttributeIds($attributeFilters)
    {
        return Attribute::whereIn('slug', array_keys($attributeFilters))->pluck('id');
    }

    private function getAttributeValueIds($attributeFilters)
    {
        return once(function () use ($attributeFilters) {
            return AttributeValue::whereIn('value', array_flatten($attributeFilters))
                ->pluck('id')
                ->implode(',') ?: 'null';
        });
    }
}
