<?php

namespace Modules\Program\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Program\Entities\Program;
use Modules\Core\Http\Requests\Request;

class SaveProgramRequest extends Request
{
    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = 'program::attributes';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'slug' => $this->getSlugRules(),
            'is_active' => 'required|boolean',
        ];
    }

    private function getSlugRules()
    {
        $rules = $this->route()->getName() === 'admin.programs.update'
            ? ['required']
            : ['nullable'];

        $slug = Program::withoutGlobalScope('active')->where('id', $this->id)->value('slug');

        $rules[] = Rule::unique('programs', 'slug')->ignore($slug, 'slug');

        return $rules;
    }
}
