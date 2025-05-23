<?php

namespace App\Http\Requests\Users\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CompleteProfileRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|phone|unique:users,phone,' . $this->user()->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'fcm_token' => 'nullable|string|max:255',
        ];
    }
}