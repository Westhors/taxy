<?php

namespace App\Http\Requests\Users\Auth;

use App\Rules\EmailOrPhone;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email_or_phone' => 'required|email_or_phone',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email_or_phone.required' => 'The email or phone number field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters long.',
        ];
    }
}