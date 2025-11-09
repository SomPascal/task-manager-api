<?php

namespace App\Http\Controllers;

use App\Dtos\CreateTaskDto;
use App\Factories\CreateTaskDtoFactory;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskRepository $taskRepository
    ) {
        $this->channel = 'task';
    }

    public function create(CreateTaskRequest $request): JsonResponse
    {
        try {
            $createTaskDto = CreateTaskDtoFactory::fromRequest($request);
            $createdTask = $this->taskRepository->create($createTaskDto);
    
            return $this->success(
                status: 201,
                message: 'Task successfuly created',
                data: new TaskResource($createdTask)
            );
        } catch (\Throwable $th) {
            $this->logException('Could not create a task', $th);

            return $this->failServerError();
        }
    }
}
