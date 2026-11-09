<?php

namespace App\Http\Requests\Admin;

class SliderRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:10240'],
        ];
    }
}
