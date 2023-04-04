<?php

namespace Smis\Console\Commands;

use Spatie\Backup\Commands\BackupCommand;

class BackupStorageCommand extends BackupCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:storage {--filename=} {--only-db} {--db-name=*} {--only-files} {--only-to-disk=} {--disable-notifications} {--timeout=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rewrites backup:run command get only storage.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //rewrite config for included folders
        config(['backup.backup.source.files.include' => [storage_path()]]);

        // add only files option
        $this->input->setOption('only-files', true);

        return parent::handle();
    }
}
