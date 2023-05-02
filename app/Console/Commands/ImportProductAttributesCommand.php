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

    private $items = [];
    private $products = [];
    private $attributes = [];
    private $attributeValues = [];
    
    public function handle()
    {
        $this->getProducts();
    }

    private function getProducts($page = 1)
    {
        try {
            $response = $this->getRequest("/products/attributes?page={$page}");
            $this->items = collect($this->items)->merge($response->items);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info("Product Attributes Page {$page}: {$e->getMessage()}");
            return;
        }

        if($page % 50 == 0) {
            foreach($this->items as $item) {
                foreach($item->properties as $property) {
                    $this->products[$item->product_id][$property->name_id][] = $property;
                    $this->attributes[] = [
                        'id' => $property->name_id,
                        'name' => $property->name,
                        'slug' => $this->generateSlug($property->name)
                    ];
                    $this->attributeValues[$property->name_id][] = [
                        'id' => $property->value_id ?? null,
                        'value' => $property->value,
                    ];
                }
            }

            Attribute::upsert( $this->attributes, ['id'], ['name', 'slug'] );

            foreach($this->attributeValues as $attributeId => $values) {
                $attribute = Attribute::find($attributeId);

                if($attribute) {
                    $attribute->saveValues(array_merge($values, $attribute->load('values')->values->toArray()));
                }
            }

            foreach($this->products as $nodId => $productAttributes) {
                $product = Product::findByNodId($nodId);

                if($product && $product->attributes->isEmpty()) {
                    $productAttributeValues = [];

                    foreach($productAttributes as $attributeId => $values) {
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

                    ProductAttributeValue::insert($productAttributeValues);
                }
            }

            $this->items = [];
            $this->products = [];
            $this->attributes = [];
            $this->attributeValues = [];
        }

        if($page <= $response->total_pages) {
            self::getProducts($page+1);
        }
    }

    /**
     * Generate slug by the given value.
     *
     * @param string $value
     * @return string
     */
    private function generateSlug($value)
    {
        $slug = str_slug($value) ?: slugify($value);

        $query = Attribute::where('slug', $slug)->withoutGlobalScope('active');

        if ($query->exists()) {
            $slug .= '-' . str_random(8);
        }

        return $slug;
    }
}
