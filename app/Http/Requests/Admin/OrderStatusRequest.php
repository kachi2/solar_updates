<?php

namespace App\Http\Requests\Admin;

class OrderStatusRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'delivery' => ['required', 'integer', 'between:0,3'],
            'dispatch' => ['required', 'integer', 'between:0,3'],
            'payment' => ['required', 'integer', 'between:0,3'],
        ];
    }
}
