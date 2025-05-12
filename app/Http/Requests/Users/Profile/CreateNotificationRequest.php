<?php

namespace App\Http\Requests\Users\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string|max:50',
            'type_id' => 'nullable|integer',
            'user_id' => [
                'nullable',
                'required_without:driver_id',
                'exists:users,id',
            ],
            'driver_id' => [
                'nullable',
                'required_without:user_id',
                'exists:drivers,id',
            ],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The notification title is required.',
            'body.required' => 'The notification body is required.',
            'type.required' => 'The notification type is required.',
            'type_id.required' => 'The notification type_id is required.',
            'user_id.required_without' => 'The user_id is required if driver_id is not provided.',
            'user_id.exists' => 'The specified user does not exist.',
            'driver_id.required_without' => 'The driver_id is required if user_id is not provided.',
            'driver_id.exists' => 'The specified driver does not exist.',
        ];
    }
}
