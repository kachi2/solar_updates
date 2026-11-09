<?php

namespace App\Http\Requests\Admin;

class ProductRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $creating = $this->isMethod('post');

        return [
            'name' => [$creating ? 'required' : 'nullable', 'string', 'max:255'],
            'category_id' => [$creating ? 'required' : 'nullable', 'integer', 'exists:categories,id'],
            'description' => [$creating ? 'required' : 'nullable', 'string'],
            'price' => ['required', 'integer', 'min:1'],
            'sale_price' => ['required', 'integer', 'min:0'],
            'image' => [$creating ? 'required' : 'nullable', 'image', 'max:10240'],
            'images' => [$creating ? 'required' : 'nullable', 'array'],
            'images.*' => ['image', 'max:10240'],
            'specification' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'qty' => ['nullable', 'string'],
            'amount' => ['nullable', 'string'],
        ];
    }
}
