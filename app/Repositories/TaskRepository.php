<?php

namespace App\Repositories;

use App\Dtos\CreateTaskDto;
use App\Models\Task;

class TaskRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(CreateTaskDto $dto): Task
    {
        return Task::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'priority' => $dto->priority->value,
            'due_date' => $dto->dueDate,

            'category_id' => $dto->categoryId,
            'user_id' => auth()->id()
        ]);
    }
}
