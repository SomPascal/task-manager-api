<?php

namespace App\Http\Requests;

use App\Constants\Pagination;
use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
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
            'per_page' => [
                'required',
                'integer',
                'gte:1',
                'lte:50'
            ],

            'page' => [
                'required',
                'integer',
                'gte:1',
                'lte:255'
            ]
        ];
    }

    protected function prepareForValidation(): self
    {
        return $this->merge([
            'per_page' => $this->input('per_page', Pagination::DEFAULT_PER_PAGE),
            'page' => $this->input('page', Pagination::DEFAULT_PAGE)
        ]);
    }
}
