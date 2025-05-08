<?php

namespace App\Http\Requests\Driver\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class DriverCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'max_power' => 'required|string|max:255',
            'fuel' => 'required|string|max:255',
            'max_speed' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'capacity' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'gear_type' => 'required|string|max:255',
            'photo_car' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}

