<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shipping address forms (account address book and checkout modal).
 */
class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'default' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Address attributes in `shippings` column names.
     *
     * @return array<string, mixed>
     */
    public function shippingAttributes(): array
    {
        return [
            'receiver_name' => $this->input('name'),
            'address' => $this->input('address'),
            'receiver_phone' => $this->input('phone'),
            'receiver_email' => $this->input('email'),
            'city' => $this->input('city'),
            'state' => $this->input('state'),
            'zip_code' => $this->input('postal_code'),
        ];
    }
}
