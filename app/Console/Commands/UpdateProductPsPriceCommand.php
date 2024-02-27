<?php

namespace Smis\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Product\Entities\Product;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UpdateProductPsPriceCommand extends Command
{
    private $prices = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nod:update-product-ps-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update NOD product presale prices';

    public function handle()
    {
        $path = public_path("storage") . '/media/ps_prices.xlsx';

        try {
            $filename = 'ps_prices.xlsx';
            $file = file_get_contents($filename);
            file_put_contents($path, $file);

            $reader = IOFactory::load($path);
            $this->prices = $reader->getActiveSheet()->toArray();
        } catch(\Exception $e) {
            Log::info("Update Presale Prices: {$e->getMessage()}");
        }

        if(file_exists($path)) { unlink($path); }

        $this->updateProductPrices();
    }

    private function updateProductPrices()
    {
        foreach($this->prices as $row) {
            Product::withoutEvents(function () use ($row) {
                Product::withoutGlobalScope('active')->whereSku(trim($row[0]))->update(['ps_price' => trim($row[1])]);
            });
        }
    }
}
