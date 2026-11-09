<?php

namespace App\Http\Requests\Admin;

class CategoryRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $creating = $this->isMethod('post');

        return [
            'name' => ['required', 'string', 'max:255'],
            'image' => [$creating ? 'required' : 'nullable', 'image', 'max:10240'],
        ];
    }
}
