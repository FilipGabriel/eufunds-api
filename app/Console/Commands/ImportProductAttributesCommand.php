<?php

namespace Smis\Console\Commands;

use Illuminate\Console\Command;
use Modules\Support\Traits\NodApi;
use Illuminate\Support\Facades\Log;
use Modules\Product\Entities\Product;
use Modules\Attribute\Entities\Attribute;
use Modules\Attribute\Entities\ProductAttributeValue;

class ImportProductAttributesCommand extends Command
{
    use NodApi;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nod:import-product-attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import NOD product attributes';

    private $now = null;
    
    public function handle()
    {
        $this->now = now();
        Log::info($this->now);
        $this->getProducts();
    }

    private function getProducts($page = 1)
    {
        $items = [];
        $products = [];
        $attributes = [];
        $attributeValues = [];

        try {
            $response = $this->getRequest("/products/attributes?count=1000&page={$page}");
            $items = $response->items;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info("Product Attributes Page {$page}: {$e->getMessage()}");
        }

        foreach($items as $item) {
            if($item->product_id == '6c976d256248d34f0310ffc5b26deb53') {
                Log::info(json_encode($item));
                Log::info(json_encode($item->properties));
            }
            foreach($item->properties as $property) {
                $products[$item->product_id][$property->name_id][] = $property;
                $attributes[$property->name_id] = [
                    'id' => $property->name_id,
                    'name' => $property->name,
                    'slug' => $this->generateSlug($property->name_id, $property->name)
                ];
                $attributeValues[$property->name_id][] = [
                    'id' => $property->value_id ?? null,
                    'value' => $property->value,
                ];
            }
        }

        Attribute::upsert( array_values($attributes), ['id', 'slug'], ['name'] );

        foreach($attributeValues as $attributeId => $values) {
            $attribute = Attribute::find($attributeId);

            if($attribute) {
                $attribute->saveValues(array_merge($values, $attribute->load('values')->values->toArray()));
            }
        }

        foreach($products as $nodId => $productAttributes) {
            $product = Product::findByNodId($nodId);

            if(! $product) { continue; }

            $productAttributeValues = [];

            foreach($productAttributes as $attributeId => $values) {
                if(! Attribute::whereId($attributeId)->exists()) { continue; }

                $productAttribute = $product->attributes()->whereAttributeId($attributeId)->firstOrCreate([
                    'attribute_id' => $attributeId
                ]);

                foreach ($values as $value) {
                    if(! isset($value->value_id)) {
                        $newValue = $productAttribute->attribute->values()->create(['value' => $value->value]);
                    }

                    $productAttributeValues[] = [
                        'product_attribute_id' => $productAttribute->id,
                        'attribute_value_id' => $value->value_id ?? $newValue->id,
                    ];
                }

                $productAttribute->attribute->categories()->syncWithoutDetaching($product->categories->pluck('id')->toArray());
            }

            ProductAttributeValue::insertOrIgnore($productAttributeValues);
        }

        if($page <= $response->total_pages) {
            Log::info(now() . ' - ' . now()->diffInMinutes($this->now));
            self::getProducts($page+1);
        }

        Log::info(now() . ' - ' . now()->diffInMinutes($this->now));
    }

    /**
     * Generate slug by the given value.
     *
     * @param string $value
     * @return string
     */
    private function generateSlug($id, $value)
    {
        $slug = str_slug($value) ?: slugify($value);

        $query = Attribute::withoutGlobalScope('active')->where('slug', $slug)->where('id', '<>', $id);

        if ($query->exists()) {
            $slug .= '-' . str_random(8);
        }

        return $slug;
    }
}
