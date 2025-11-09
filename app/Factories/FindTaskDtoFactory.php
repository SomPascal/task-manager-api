<?php

namespace App\Factories;

use App\Dtos\FindTaskDto;
use App\Enums\SortOrder;
use App\Enums\TaskPriority;
use App\Http\Requests\FindTaskRequest;
use Carbon\Carbon;

class FindTaskDtoFactory
{
    public static function fromRequest(FindTaskRequest $request): FindTaskDto
    {
        $priority = $request->validated('priority');
        $sortOrder = $request->validated('sort_order');
        $pagination = PaginationDtoFactory::fromRequest($request);

        $fromDate = $request->validated('from_date');
        $endDate = $request->validated('end_date');

        return new FindTaskDto(
            categoryId: $request->validated('category_id'),
            query: $request->validated('query'),
            priority: filled($priority) ? TaskPriority::from($priority) : null,
            sortOrder: filled($sortOrder) ? SortOrder::from($sortOrder) : SortOrder::ASC,

            pagination: $pagination,

            fromDate: filled($fromDate) ? Carbon::parse($fromDate) : now(),
            endDate: filled($endDate) ? Carbon::parse($endDate) : now()
        );
    }
}
