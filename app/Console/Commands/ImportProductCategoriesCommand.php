<?php

namespace Smis\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Modules\Support\Traits\NodApi;
use Illuminate\Support\Facades\Log;
use Modules\Category\Entities\Category;

class ImportProductCategoriesCommand extends Command
{
    use NodApi;
    
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nod:import-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import NOD product categories';

    public function handle()
    {
        try {
            $response = $this->getRequest('/product-categories');
            $productCategories = $response->product_categories;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return;
        }

        foreach($productCategories as $category) {
            $this->createCategory($category);
        }
    }

    private function createCategory($category)
    {
        $values = [
            'name' => $category->name,
            'parent_id' => $category->parent_id ?? null,
            'slug' => str_slug($category->name),
            'is_active' => true,
        ];

        if(Category::find($category->id)->exists ?? false) {
            unset($values['slug']);
        }

        Category::updateOrCreate(['id' => $category->id], $values);

        foreach($category->children ?? [] as $children) {
            $this->createCategory($children);
        }
    }
}
