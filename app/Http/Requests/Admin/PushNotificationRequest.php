<?php

namespace App\Http\Requests\Admin;

class PushNotificationRequest extends AdminFormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ];
    }
}
