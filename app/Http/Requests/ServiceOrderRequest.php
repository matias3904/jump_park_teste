<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userId'         => 'required|integer|exists:users,id',
            'vehiclePlate'   => 'required|string|size:7',
            'entryDateTime'  => 'required|date_format:Y-m-d H:i:s',
            'exitDateTime'   => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:entryDateTime',
            'priceType'      => 'nullable|string|max:55',
            'price'          => 'nullable|numeric|min:0|max:9999999999.99',
        ];
    }
}
