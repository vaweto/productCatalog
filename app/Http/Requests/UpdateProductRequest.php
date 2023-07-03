<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpaceDashUnderscore;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['unique:products,name', 'max:10', new AlphaSpaceDashUnderscore()],
            'code' => 'unique:products,code|alpha_dash:ascii|lowercase|max:255',
            'category_id' => 'exists:App\Models\Category,id',
            'price' => 'decimal:0,2',
            'released_at' => 'date|after_or_equal:now',
            'tags.*' => 'exists:App\Models\Tag,id',
        ];
    }
}
