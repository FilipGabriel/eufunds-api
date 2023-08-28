<?php

namespace Modules\Program\Http\Controllers;

use Modules\Media\Entities\File;

class ProgramDownloadsController
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = File::where('id', decrypt($id))->firstOrFail();

        abort_if(is_null($file) || ! file_exists($file->realPath()), 404);

        return response()->download($file->realPath(), $file->filename);
    }
}
