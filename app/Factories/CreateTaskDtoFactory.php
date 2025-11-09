<?php

namespace App\Factories;

use App\Dtos\CreateTaskDto;
use App\Enums\TaskPriority;
use App\Http\Requests\CreateTaskRequest;
use Carbon\Carbon;

class CreateTaskDtoFactory
{
    public static function fromRequest(CreateTaskRequest $request): CreateTaskDto
    {
        $dueDate = $request->validated('due_date');

        return new CreateTaskDto(
            title: $request->validated('title'),
            categoryId: $request->validated('category_id'),
            priority: TaskPriority::from($request->validated('priority')),
            description: $request->validated('description'),
            dueDate: filled($dueDate) ? Carbon::parse($dueDate) : null
        );
    }
}
