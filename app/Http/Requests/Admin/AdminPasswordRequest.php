<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;

class AdminPasswordRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'oldPassword' => ['required', 'current_password:admin'],
            'password' => ['required', 'string', 'min:5', 'different:oldPassword'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'oldPassword.current_password' => 'Old Password is Incorrect',
            'password.different' => 'Old / New Password Cannot be the Same',
        ];
    }

    /**
     * The profile page reads the `pass` / `alert` session keys.
     */
    protected function failedValidation(Validator $validator): void
    {
        session()->flash('alert', 'alert-danger');
        session()->flash('pass', $validator->errors()->first());

        parent::failedValidation($validator);
    }
}
