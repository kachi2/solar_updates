<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed', 'different:oldpassword'],
            'oldpassword' => ['required_with:password', 'nullable', 'current_password'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'oldpassword.current_password' => 'Old Password is Incorrect',
            'password.different' => 'Old Password / New Password Cannot be the Same',
        ];
    }

    /**
     * The account page shows the `error` session key.
     */
    protected function failedValidation(Validator $validator): void
    {
        session()->flash('error', $validator->errors()->first());

        parent::failedValidation($validator);
    }
}
