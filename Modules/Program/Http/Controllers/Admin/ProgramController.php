<?php

namespace Modules\Program\Http\Controllers\Admin;

use Modules\Program\Entities\Program;
use Modules\Admin\Traits\HasCrudActions;
use Modules\Program\Http\Requests\SaveProgramRequest;

class ProgramController
{
    use HasCrudActions;

    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = Program::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'program::programs.program';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'program::admin.programs';

    /**
     * Form requests for the resource.
     *
     * @var array|string
     */
    protected $validation = SaveProgramRequest::class;

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Program::with(['files', 'categories', 'list_categories'])->withoutGlobalScope('active')->find($id);
    }

    /**
     * Destroy resources by given ids.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Program::withoutGlobalScope('active')
            ->findOrFail($id)
            ->delete();

        return back()->withSuccess(trans('admin::messages.resource_deleted', ['resource' => $this->getLabel()]));
    }
}
