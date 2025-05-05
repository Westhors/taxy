<?php

namespace App\Http\Requests\Users\Orders;

use Illuminate\Foundation\Http\FormRequest;

class AcceptOrderRequestRequest extends FormRequest
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
            'order_request_id' => 'required|integer|exists:order_requests,id',
        ];
    }

    public function messages(): array
    {
        return [
            'order_request_id.required' => 'The order request ID is required.',
            'order_request_id.integer'  => 'The order request ID must be an integer.',
            'order_request_id.exists'   => 'The selected order request does not exist.',
        ];
    }
}
