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
            // Leave both empty (or 0) for "Price on request": the shop shows "Learn more" and WhatsApp only.
            'price' => ['nullable', 'integer', 'min:0'],
            'sale_price' => ['nullable', 'integer', 'min:0'],
            'image' => [$creating ? 'required' : 'nullable', 'image', 'max:10240'],
            'images' => [$creating ? 'required' : 'nullable', 'array'],
            'images.*' => ['image', 'max:10240'],
            'specification' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'qty' => ['nullable', 'string'],
            'amount' => ['nullable', 'string'],
        ];
    }
}
