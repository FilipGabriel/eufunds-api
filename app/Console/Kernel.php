<?php

namespace Smis\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ScaffoldModuleCommand::class,
        Commands\ScaffoldEntityCommand::class,
        Commands\ImportProductsCommand::class,
        Commands\ImportProductInfoCommand::class,
        Commands\UpdateProductPsPriceCommand::class,
        Commands\ImportProductCategoriesCommand::class,
        Commands\ImportProductAttributesCommand::class,
        Commands\ImportProductManufacturersCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command("nod:import-categories")->daily()->timezone('Europe/Bucharest')->at('01:00');
        $schedule->command("nod:import-manufacturers")->daily()->timezone('Europe/Bucharest')->at('01:15');
        $schedule->command("nod:import-products")->daily()->timezone('Europe/Bucharest')->at('01:30');
        // $schedule->command("nod:import-product-attributes")->daily()->timezone('Europe/Bucharest')->at('08:00');
        $schedule->command("nod:import-product-info")->hourly()->timezone('Europe/Bucharest')->between('08:00', '00:00');
    }
}
