<?php

namespace App\Http\Requests\Users\Orders;

use Illuminate\Foundation\Http\FormRequest;

class CancelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('user')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
            'cancel_reason' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'رقم الطلب مطلوب.',
            'order_id.integer' => 'رقم الطلب يجب أن يكون رقمًا صحيحًا.',
            'order_id.exists' => 'الطلب غير موجود.',
            'cancel_reason.required' => 'سبب الإلغاء مطلوب.',
        ];
    }
}
