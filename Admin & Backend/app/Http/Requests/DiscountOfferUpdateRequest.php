<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountOfferUpdateRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'value' => 'required|numeric|min:0',
            'status' => 'in:active,inactive',
            'starts_date' => 'required|date',
            'ends_date' => 'nullable|date|after_or_equal:starts_date',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $product = \App\Models\Product::find($this->product_id);
            if ($product && $this->type === 'fixed' && $this->value >= $product->price) {
                $validator->errors()->add('value', 'Value must be less than product price.');
            }
            if ($this->type === 'percentage' && ($this->value < 0 || $this->value > 100)) {
                $validator->errors()->add('value', 'Percentage value must be between 0 and 100.');
            }
        });
    }
}
