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
}
