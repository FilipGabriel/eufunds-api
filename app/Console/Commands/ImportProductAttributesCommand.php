<?php

namespace Smis\Console\Commands;

use Illuminate\Console\Command;
use Modules\Support\Traits\NodApi;
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
    
    public function handle()
    {
        $currentPage = 1;
        $batchSize = 1000;

        $response = $this->getRequest("/products/attributes?count={$batchSize}&page={$currentPage}");
        
        while ($currentPage <= $response->total_pages) {
            $items = $response->items;
            $products = [];
            $attributes = [];
            $attributeValues = [];

            foreach($items as $item) {
                foreach($item->properties as $property) {
                    $productId = $item->product_id;
                    $nameId = $property->name_id;
                    $valueId = $property->value_id ?? null;
                    $propertyName = $property->name;
                    $propertyValue = $property->value;
    
                    $products[$productId][$nameId][] = $property;
                    $attributes[$nameId] = [
                        'id' => $nameId,
                        'name' => $propertyName,
                        'slug' => $this->generateSlug($nameId, $propertyName)
                    ];
                    
                    if($valueId) {
                        $attributeValues[$nameId][] = [
                            'id' => $valueId,
                            'value' => $propertyValue,
                        ];
                    }
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
                            $newValue = $productAttribute->attribute->values()->firstOrCreate(['value' => $value->value]);
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

            // Increment current page
            $currentPage++;
            
            // Fetch the next batch of data
            $response = $this->getRequest("/products/attributes?count={$batchSize}&page={$currentPage}");
        }
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
