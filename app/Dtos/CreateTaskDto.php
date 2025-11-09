<?php

namespace App\Dtos;

use App\Enums\TaskPriority;
use Carbon\Carbon;

final readonly class CreateTaskDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $title,
        public int $categoryId,
        public TaskPriority $priority,
        public ?string $description = null,
        public ?Carbon $dueDate = null
    )
    {
        //
    }
}
