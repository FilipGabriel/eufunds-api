<?php

namespace Smis\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class InstallRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'db.host' => 'required',
            'db.port' => 'required',
            'db.username' => 'required',
            'db.password' => 'nullable',
            'db.database' => 'required',
            'admin.first_name' => 'required',
            'admin.last_name' => 'required',
            'admin.email' => 'required|email',
            'admin.phone' => 'required',
            'admin.password' => 'required|confirmed|min:6',
            'app.app_name' => 'required',
            'app.app_email' => 'required|email',
            'app.app_phone' => 'required',
            'app.search_engine' => ['required', Rule::in(['mysql', 'algolia'])],
            'app.algolia_app_id' => 'required_if:app.search_engine,algolia',
            'app.algolia_secret' => 'required_if:app.search_engine,algolia',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            '*.required' => 'The :attribute field is required.',
            '*.required_if' => 'The :attribute field is required when :other is :value.',
            '*.email' => 'The :attribute must be a valid email address.',
            '*.unique' => 'The :attribute has already been taken.',
            '*.confirmed' => 'The :attribute confirmation does not match.',
            '*.min' => 'The :attribute must be at least :min characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'db.host' => 'host',
            'db.port' => 'port',
            'db.username' => 'username',
            'db.password' => 'password',
            'db.database' => 'datbase',
            'admin.first_name' => 'first name',
            'admin.last_name' => 'last name',
            'admin.email' => 'email',
            'admin.password' => 'password',
            'app.app_name' => 'store name',
            'app.app_email' => 'store email',
            'app.search_engine' => 'search engine',
            'app.algolia_app_id' => 'algolia app id',
            'app.algolia_secret' => 'algolia secret',
        ];
    }
}
