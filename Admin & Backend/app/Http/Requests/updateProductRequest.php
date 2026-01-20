<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateProductRequest extends FormRequest
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
            'name' => ['required', Rule::unique('products')->ignore($this->route('id')),
            ],
            'price' => [
                'required',
                'numeric',
            ], 'stock' => [
                'required',
                'numeric',
            ],
            'slug' => [
                'required',
                Rule::unique('products', 'slug')->ignore($this->route('id')),
            ],
            'description' => 'nullable|max:255',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
