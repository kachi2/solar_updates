<?php

namespace App\Http\Requests\Admin;

class ProjectRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $creating = $this->routeIs('admin.project.store');

        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'images' => [$creating ? 'required' : 'nullable', 'array'],
            'images.*' => ['image', 'max:10240'],
        ];
    }
}
