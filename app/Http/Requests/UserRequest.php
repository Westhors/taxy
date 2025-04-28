<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'currency' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'nullable|string',
            'balance' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $this->user?->id,
            'phone' => 'nullable|string|max:20',
            'password' =>'nullable|string|min:6',
            'logo' => 'nullable|image|max:2048',
            'country_id' => 'nullable|exists:countries,id',
        ];

        return $rules;
    }
}

