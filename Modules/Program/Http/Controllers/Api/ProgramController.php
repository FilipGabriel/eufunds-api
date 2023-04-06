<?php

namespace Modules\Program\Http\Controllers\Api;

use Modules\Program\Entities\Program;

class ProgramController
{
    /**
     * Display program tree in json.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'programs' => Program::tree(),
            'activePrograms' => Program::searchable()
        ]);
    }
}
