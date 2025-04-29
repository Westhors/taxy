<?php

namespace App\Http\Requests\Users\Auth;

use App\Rules\EmailOrPhone;
use Illuminate\Foundation\Http\FormRequest;

class VerifyOTPEmailOrPhoneRequest extends FormRequest
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
            'email_or_phone' => 'required|string|email_or_phone',
            'otp' => 'required|digits:5',
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
        ];
    }
}
