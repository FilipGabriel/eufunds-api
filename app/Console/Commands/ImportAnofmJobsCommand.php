<?php

namespace Smis\Console\Commands;

use Illuminate\Console\Command;
use Modules\Job\Actions\AnofmApi;

class ImportAnofmJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:import-anofm-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import jobs from ANOFM';

    public function handle()
    {
        (new AnofmApi())->getJobs();
    }
}
