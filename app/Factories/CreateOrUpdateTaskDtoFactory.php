<?php

namespace App\Factories;

use App\Dtos\CreateOrUpdateTaskDto;
use App\Enums\TaskPriority;
use App\Http\Requests\CreateOrUpdateTaskRequest;
use Carbon\Carbon;

class CreateOrUpdateTaskDtoFactory
{
    public static function fromRequest(CreateOrUpdateTaskRequest $request): CreateOrUpdateTaskDto
    {
        $dueDate = $request->validated('due_date');

        return new CreateOrUpdateTaskDto(
            title: $request->validated('title'),
            categoryId: $request->validated('category_id'),
            priority: TaskPriority::from($request->validated('priority')),
            description: $request->validated('description'),
            dueDate: filled($dueDate) ? Carbon::parse($dueDate) : null
        );
    }
}
