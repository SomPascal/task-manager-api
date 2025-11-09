<?php

namespace App\Dtos;

use App\Enums\SortOrder;
use App\Enums\TaskPriority;
use Carbon\Carbon;

final readonly class FindTaskDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public int $categoryId,
        public ?string $query = null,
        public ?TaskPriority $priority = null,
        public SortOrder $sortOrder = SortOrder::ASC,

        public PaginationDto $pagination,

        public Carbon $fromDate,
        public Carbon $endDate
    )
    {
        //
    }
}
