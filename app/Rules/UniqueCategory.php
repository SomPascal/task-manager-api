<?php

namespace App\Rules;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Check if a category is unique per user
 */
class UniqueCategory implements ValidationRule
{
    public function __construct(
        protected ?string $ignoreName = null
    ) {

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = auth('sanctum')->user();

        if (empty($user)) {
            $fail('You should be logged in');
            return;
        }

        $exists = app(CategoryRepository::class)
        ->existsForUser($value, $user->id, $this->ignoreName);

        if ($exists) {
            $fail('This category name already exists');
        }
    }
}
