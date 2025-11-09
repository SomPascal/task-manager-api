<?php

namespace App\Repositories;

use App\Dtos\CreateOrUpdateTaskDto;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function findOrFail(int $taskId, array $with = []): Task
    {
        return Task::with($with)
        ->where('id', $taskId)
        ->firstOrFail();
    }

    public function findUserTaskOrFail(int $taskId, int $userId, array $with = []): Task
    {
        return Task::with($with)
        ->where('user_id', $userId)
        ->where('id', $taskId)
        ->firstOrFail();
    }

    public function findUserTaskOrFailWithTrashed(int $taskId, int $userId, array $with = []): Task
    {
        return Task::withTrashed()->with($with)
        ->where('user_id', $userId)
        ->where('id', $taskId)
        ->firstOrFail();
    }

    public function create(CreateOrUpdateTaskDto $dto): Task
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

    public function update(CreateOrUpdateTaskDto $dto, int $taskId): Task
    {
        $task = $this->findUserTaskOrFail($taskId, auth()->id());

        $task->update([
            'title' => $dto->title,
            'description' => $dto->description,
            'priority' => $dto->priority->value,
            'due_date' => $dto->dueDate
        ]);
        $task->refresh();

        return $task;
    }

    public function delete(int $taskId): bool
    {
        $task = $this->findUserTaskOrFail($taskId, auth()->id());

        return DB::transaction(function () use ($task): bool {
            $task->update(['pinned' => false]);
            return (bool) $task->delete();
        });
    }

    public function restore(int $taskId): bool
    {
        $task = $this->findUserTaskOrFailWithTrashed($taskId, auth()->id());
        return (bool) $task->restore();
    }

    public function done(int $taskId): Task
    {
        $task = $this->findUserTaskOrFail($taskId, auth()->id());
        $data = ['done_at' => now()];

        if ($task->pinned) {
            $data['pinned'] = false;
        }

        $task->update($data);
        $task->refresh();

        return $task;
    }

    public function undone(int $taskId): Task
    {
        $task = $this->findUserTaskOrFail($taskId, auth()->id());

        $task->update([
            'done_at' => null
        ]);
        $task->refresh();

        return $task;
    }

    public function unpin(int $taskId): Task
    {
        $task = $this->findUserTaskOrFail($taskId, auth()->id());

        $task->update([
            'pinned' => false
        ]);
        $task->refresh();

        return $task;
    }

    public function pin(int $taskId): Task
    {
        $task = $this->findUserTaskOrFail($taskId, auth()->id());

        return DB::transaction(function () use ($task): Task {
            $task->update([
                'pinned' => true
            ]);

            Task::where('id', '!=', $task->id)
            ->where('user_id', auth()->id())
            ->where('pinned', true)
            ->update(['pinned' => false]);

            $task->refresh();

            return $task;
        });
    }
}
