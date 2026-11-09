<?php

namespace App\Http\Requests\Admin;

class NewsRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $creating = $this->routeIs('admin.news.store');

        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => [$creating ? 'required' : 'nullable', 'image', 'max:10240'],
            'images' => [$creating ? 'required' : 'nullable', 'array'],
            'images.*' => ['image', 'max:10240'],
        ];
    }
}
