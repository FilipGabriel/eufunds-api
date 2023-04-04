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
        Commands\BackupStorageCommand::class,
        Commands\ImportAnofmJobsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $date = date('Y-m-d-H-i-s');
        $schedule->command("backup:clean")->daily()->timezone('Europe/Bucharest')->at('01:00');
        $schedule->command("job:import-anofm-jobs")->daily()->timezone('Europe/Bucharest')->at('03:00');
        $schedule->command("backup:run --filename=full-{$date}.zip")->daily()->timezone('Europe/Bucharest')->at('01:30');

        $stateBackupTimes = [
            '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'
        ];
        foreach ($stateBackupTimes as $time) {
            $schedule->command("backup:run --only-db --filename=database-{$date}.zip")->daily()->timezone('Europe/Bucharest')->at($time);
            $schedule->command("backup:storage --filename=storage-{$date}.zip")->daily()->timezone('Europe/Bucharest')->at($time);
        }
    }
}
