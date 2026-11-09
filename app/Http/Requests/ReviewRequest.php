<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Links in reviews are how the 2025–2026 spam bots operated, so they are refused.
     */
    private const NO_LINKS = 'not_regex:/(https?:\/\/|www\.|<a\s|\[url)/i';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', self::NO_LINKS],
            'email' => ['required', 'email', 'max:255'],
            'rated' => ['required', 'integer', 'between:1,5'],
            'description' => ['required', 'string', 'min:3', 'max:2000', self::NO_LINKS],
            // Honeypot: hidden from people, filled in by bots.
            'website' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.not_regex' => 'Please remove links from your name.',
            'description.not_regex' => 'Links are not allowed in reviews.',
            'website.prohibited' => 'Your review could not be submitted.',
        ];
    }
}
