<?php

namespace Modules\Program\Http\Controllers\Api;

use Modules\Media\Entities\File;
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
    
    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        return response()->json([
            'types' => Program::findBySlug($slug)->types
        ]);
    }
    
    /**
     * Display the specified resource.
     */
    public function listCategories($slug)
    {
        $program = Program::findBySlug($slug);

        return response()->json([
            'program' => $program,
            'list_categories' => $program->list_categories->map(function ($program) {
                return [
                    'slug' => $program->slug,
                    'name' => $program->name,
                    'logo' => $program->logo->path ?? null,
                ];
            })
        ]);
    }

    public function download($id)
    {
        $file = File::where('id', decrypt($id))->firstOrFail();

        abort_if(is_null($file) || ! file_exists($file->realPath()), 404);

        return response()->download($file->realPath(), $file->filename);
    }
}
