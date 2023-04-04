<?php

namespace Smis\Actions\Fortify;

use Modules\User\Entities\User;
use Modules\User\Entities\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\User\Events\CustomerRegistered;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [ 'required', 'string', 'email', 'max:255', Rule::unique(User::class) ],
            'password' => $this->passwordRules(),
            'phone' => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            $errors = json_decode($validator->errors(), true);
            foreach ($errors as $errorsGroup) {
                foreach($errorsGroup as $error) {
                    throw new HttpResponseException(response()->json([
                        'message' => $error,
                    ], 422));
                }
            }
        }

        $user = User::create([
            'name' => $input['name'],
            'username' => $this->generateSlug($input['name']),
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'phone' => $input['phone'],
        ]);

        $this->assignCustomerRole($user);

        event(new CustomerRegistered($user));

        return $user;
    }

    protected function assignCustomerRole($user)
    {
        $role = Role::findOrNew(setting('customer_role'));

        if ($role->exists) {
            $user->roles()->sync($role);
        }
    }

    /**
     * Generate slug by the given value.
     *
     * @param string $value
     * @return string
     */
    private function generateSlug($value)
    {
        $slug = str_slug($value) ?: slugify($value);

        $query = User::where('username', $slug)->withoutGlobalScope('active');

        if ($query->exists()) {
            $slug .= '-' . str_random(8);
        }

        return $slug;
    }
}
