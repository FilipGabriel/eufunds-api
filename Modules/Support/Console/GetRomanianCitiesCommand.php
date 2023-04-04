<?php

namespace Modules\Support\Console;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Modules\Support\Entities\Settlement;
use Modules\Support\State;

class GetRomanianCitiesCommand extends Command
{
    const HTTPS_SETTLEMENTS = "https://roloca.coldfuse.io/orase/%s";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settlements:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import romanian settlements';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            if (
                Settlement::count() &&
                !$this->confirm(
            "Settlements table is not empty.\n " .
                    "If you continue it's possible that IDs will change.\n " .
                    "Are you sure?"
                )
            ) {
                return 0;
            }

            Settlement::query()->truncate();
            $this->info("Table truncated");

            $counties = State::get('RO');
            foreach ($counties as $countyCode => $countyName) {
                if ('B' == $countyCode) {
                    Settlement::create([
                        'county_code' => 'B',
                        'county' => 'Bucuresti',
                        'name' => 'Bucuresti',
                        'full_name' => 'Bucuresti'
                    ]);
                    $this->info("Added Bucuresti.");
                } else {
                    $response = (new Client())->get(sprintf(self::HTTPS_SETTLEMENTS, $countyCode));
                    $settlements = json_decode($response->getBody()->getContents(), true);

                    foreach ($settlements as $settlement) {
                        $fullName = $settlement['nume'] .
                            (array_key_exists('comuna', $settlement) ? " - {$settlement['comuna']}" : "") .
                            " ({$countyName})";

                        Settlement::create([
                            'county_code' => $countyCode,
                            'county' => $countyName,
                            'name' => $settlement['nume'],
                            'village' => $settlement['comuna'] ?? null,
                            'full_name' => $fullName
                        ]);
                        $this->info("Added {$fullName}.");
                    }
                }
            }

            return 0;
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());

            return 1;
        }
    }
}
