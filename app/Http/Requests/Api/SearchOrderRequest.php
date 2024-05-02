<?php

namespace App\Http\Requests\Api;

use App\Enums\Order\OrderStatusEnums;
use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchOrderRequest extends FormRequest
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
            'phone_number' => ['nullable', new PhoneNumberRule()],
            'national_code' => ['nullable', 'numeric', 'max_digits:10'],
            'min' => ['nullable', 'integer', 'min:0', 'lte:max'],
            'max' => ['nullable', 'integer', 'min:0', 'gte:min'],
            'status' => ['nullable', 'string', Rule::in(OrderStatusEnums::values())],
        ];
    }
}
