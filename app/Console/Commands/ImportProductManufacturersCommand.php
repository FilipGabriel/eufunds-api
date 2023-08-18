<?php

namespace Smis\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Modules\Brand\Entities\Brand;
use Modules\Support\Traits\NodApi;
use Illuminate\Support\Facades\Log;

class ImportProductManufacturersCommand extends Command
{
    use NodApi;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nod:import-manufacturers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import NOD product manufacturers';

    public function handle()
    {
        try {
            $response = $this->getRequest('/manufacturers');
            $manufacturers = $response->manufacturers;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return;
        }

        foreach($manufacturers as $manufacturer) {
            $this->createManufacturer($manufacturer);
        }
    }

    private function createManufacturer($manufacturer)
    {
        $values = [
            'name' => $manufacturer->name,
            'slug' => str_slug($manufacturer->name),
            'is_active' => true,
        ];

        if(Brand::whereNodId($manufacturer->id)->exists()) {
            unset($values['slug']);
        }

        Brand::updateOrCreate(['nod_id' => $manufacturer->id], $values);
    }
}
