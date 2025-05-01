<?php

namespace App\Http\Requests\Driver\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class DriverCompleteProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // السماح للجميع
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|phone|unique:drivers,phone,' . $this->user()->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
        ];
    }
}
