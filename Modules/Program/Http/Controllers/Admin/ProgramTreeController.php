<?php

namespace Modules\Program\Http\Controllers\Admin;

use Modules\Program\Entities\Program;
use Modules\Program\Services\ProgramTreeUpdater;
use Modules\Program\Http\Responses\ProgramTreeResponse;

class ProgramTreeController
{
    /**
     * Display program tree in json.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Program::withoutGlobalScope('active')
            ->orderByRaw('-position DESC')
            ->get();

        return new ProgramTreeResponse($programs);
    }

    /**
     * Update program tree in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        ProgramTreeUpdater::update(request('program_tree'));

        return trans('program::messages.program_order_saved');
    }
}
