<?php

namespace App\Http\Requests\Admin;

/**
 * Forms with a single required `name` field (menus, sub categories).
 */
class NameRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
