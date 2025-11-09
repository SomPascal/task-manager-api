<?php

namespace App\Http\Requests;

use App\Enums\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:500'],

            'category_id' => [
                'required',
                'integer',
                'gte:1',
                'exists:categories,id'
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'priority' => [
                'required',
                'string',
                Rule::in(TaskPriority::values())
            ],

            'due_date' => [
                'nullable',
                Rule::date()
                ->format('Y-m-d H:i:s')
                ->afterOrEqual(now())
            ]
        ];
    }

    protected function prepareForValidation(): self
    {
        return $this->merge([
            'priority' => $this->input('priority', TaskPriority::LOW->value)
        ]);
    }
}
