<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountOfferRequest extends FormRequest
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
            'type' => 'required|in:fixed,percentage',
            'products' => 'array',
            'value' => 'required|numeric|min:0',
            'status' => 'in:active,inactive',
            'starts_date' => 'required|date',
            'ends_date' => 'nullable|date|after_or_equal:starts_date',
        ];
    }
}
