<?php

namespace App\Http\Requests\Admin;

class ExchangeRateRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rate' => ['required', 'integer', 'min:1'],
        ];
    }
}
