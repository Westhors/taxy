<?php

namespace App\Http\Requests\Users\Orders;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
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
            // Order and Transport Types
            'order_type' => ['required', Rule::in(['people', 'shipment'])],
            'transport_type' => ['required', Rule::in(['car', 'bike'])],

            // Location data
            'pick_lat' => ['required', 'numeric', 'between:-90,90'],
            'pick_lng' => ['required', 'numeric', 'between:-180,180'],
            'pick_address' => ['nullable', 'string', 'max:255'],
            'drop_lat' => ['required', 'numeric', 'between:-90,90'],
            'drop_lng' => ['required', 'numeric', 'between:-180,180'],
            'drop_address' => ['nullable', 'string', 'max:255'],

            // Shipment details
            'sender_name' => ['nullable', 'string', 'max:255'],
            'sender_phone' => ['nullable', 'string', 'phone', 'max:20'],
            'sender_remark' => ['nullable', 'string'],
            'receiver_name' => ['nullable', 'string', 'max:255'],
            'receiver_phone' => ['nullable', 'string', 'phone', 'max:20'],
            'receiver_remark' => ['nullable', 'string'],
            'shipment_type' => ['nullable', 'string', 'max:50'],
            'shipment_details' => ['nullable', 'string'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'string'],
            'is_breakable' => ['nullable', 'boolean'],

            // Schedule and Pricing
            'schedule_time' => ['nullable', 'date', 'after_or_equal:now'],
            'expected_price' => ['nullable', 'numeric', 'min:0'],
            'kms_num' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'order_type' => 'Order Type',
            'transport_type' => 'Transport Type',
            'pick_lat' => 'Pick-up Latitude',
            'pick_lng' => 'Pick-up Longitude',
            'pick_address' => 'Pick-up Address',
            'drop_lat' => 'Drop-off Latitude',
            'drop_lng' => 'Drop-off Longitude',
            'drop_address' => 'Drop-off Address',
            'sender_name' => 'Sender Name',
            'sender_phone' => 'Sender Phone',
            'sender_remark' => 'Sender Remark',
            'receiver_name' => 'Receiver Name',
            'receiver_phone' => 'Receiver Phone',
            'receiver_remark' => 'Receiver Remark',
            'shipment_type' => 'Shipment Type',
            'shipment_details' => 'Shipment Details',
            'weight' => 'Weight',
            'dimensions' => 'Dimensions',
            'is_breakable' => 'Is Breakable',
            'schedule_time' => 'Schedule Time',
            'expected_price' => 'Expected Price',
            'kms_num' => 'Numbers of KMs',
        ];
    }
}