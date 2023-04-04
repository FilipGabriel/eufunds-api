<?php

namespace Modules\Program\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Program\Entities\Program;

class ProgramDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Program::class, 20)->create();
    }
}
