<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Base request for admin forms. Admin pages show feedback from the
 * `alert` / `message` session keys, so validation failures set those too.
 */
abstract class AdminFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('alert', 'error');
        session()->flash('message', $validator->errors()->first());

        parent::failedValidation($validator);
    }
}
