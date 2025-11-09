<?php

namespace App\Http\Requests;

use App\Enums\SortOrder;
use App\Enums\TaskPriority;
use Illuminate\Validation\Rule;

class FindTaskRequest extends PaginationRequest
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
            ...[
                'category_id' => [
                    'required',
                    'integer',
    
                    Rule::exists('categories', 'id')
                    ->whereNull('deleted_at')
                    ->where('user_id', auth()->id())
                ],
    
                'query' => [
                    'nullable',
                    'string',
                    'max:255'
                ],

                'priority' => [
                    'nullable',
                    'string',
                    Rule::in(TaskPriority::values())
                ],
    
                'sort_order' => [
                    'nullable',
                    'string',
                    Rule::in(SortOrder::cases())
                ],

                'from_date' => [
                    'nullable',
                    Rule::date()->afterOrEqual(now())
                ],
    
                'end_date' => [
                    'nullable',
                    Rule::date()->afterOrEqual('from_date')
                ]
            ],
            ...parent::rules()
        ];
    }
}
