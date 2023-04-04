<?php

namespace Modules\Core\Http\Requests;



use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class SimpleErrorRequest extends Request
{
    protected function failedValidation(Validator $validator)
    {
        $errors = json_decode($validator->errors(), true);
        foreach ($errors as $errorsGroup) {
            foreach($errorsGroup as $error) {
                throw new HttpResponseException(response()->json([
                    'message' => $error,
                ], 422));
            }
        }

        parent::failedValidation($validator);
    }
}
