<?php

namespace Modules\Program\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ProgramTreeResponse implements Responsable
{
    /**
     * Programs collection.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $programs;

    /**
     * Create a new instance.
     *
     * @param \Illuminate\Database\Eloquent\Collection $programs
     */
    public function __construct($programs)
    {
        $this->programs = $programs;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return response()->json($this->transform());
    }

    /**
     * Transform the programs.
     *
     * @return \Illuminate\Support\Collection
     */
    private function transform()
    {
        return $this->programs->map(function ($program) {
            return [
                'id' => $program->id,
                'parent' => $program->parent_id ?: '#',
                'text' => $program->name,
                'data' => [
                    'position' => $program->position,
                ],
            ];
        });
    }
}
