<?php

namespace App\Http\Controllers;

use App\Factories\CreateOrUpdateTaskDtoFactory;
use App\Factories\FindTaskDtoFactory;
use App\Http\Requests\CreateOrUpdateTaskRequest;
use App\Http\Requests\FindTaskRequest;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(
        protected TaskRepository $taskRepository
    ) {
        $this->channel = 'task';
    }

    public function index(FindTaskRequest $request): JsonResponse
    {
        try {
            $findTaskDto = FindTaskDtoFactory::fromRequest($request);
            $paginatedTasks = $this->taskRepository->filter($findTaskDto);
    
            return $this->success(
                message: 'Tasks successfuly filtered',
                data: new PaginatedCollection(
                    resource: $paginatedTasks,
                    collects: TaskResource::class,
                    resourceKey: 'tasks'
                )
            );
        } catch (\Throwable $th) {
            $this->logException('Could not filter tasks', $th);

            return $this->failServerError();
        }
    }

    public function show(int $taskId): JsonResponse
    {
        try {
            $task = $this->taskRepository->findUserTaskOrFail(
                $taskId,
                auth()->id()
            );

            return $this->success(
                status: 200,
                message: 'Task successfuly retrieved',
                data: new TaskResource($task)
            );
        } catch (ModelNotFoundException $e) {

            return $this->fail(
                message: 'No task retrieved',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not show a task', $th);

            return $this->failServerError();
        }
    }

    public function create(CreateOrUpdateTaskRequest $request): JsonResponse
    {
        try {
            $createTaskDto = CreateOrUpdateTaskDtoFactory::fromRequest($request);
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

    public function update(CreateOrUpdateTaskRequest $request, int $taskId)
    {
        try {
            $updateTaskDto = CreateOrUpdateTaskDtoFactory::fromRequest($request);
            $updatedTask = $this->taskRepository->update($updateTaskDto, $taskId);
    
            return $this->success(
                message: 'Task successfuly updated',
                data: new TaskResource($updatedTask),
                status: 200
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No task found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException(
                'Could not update a task',
                $th
            );

            return $this->failServerError();
        }
    }

    public function pin(int $taskId): JsonResponse
    {
        try {
            $pinnedTask = $this->taskRepository->pin($taskId);

            return $this->success(
                message: 'Task successfuly pinned',
                data: new TaskResource($pinnedTask)
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No task found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not pin a task', $th);

            return $this->failServerError();
        }
    }

    public function unpin(int $taskId): JsonResponse
    {
        try {
            $unpinnedTask = $this->taskRepository->unpin($taskId);

            return $this->success(
                message: 'Task successfuly unpinned',
                data: new TaskResource($unpinnedTask)
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No task found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not unpin a task', $th);

            return $this->failServerError();
        }
    }

    public function done(int $taskId): JsonResponse
    {
        try {
            $doneTask = $this->taskRepository->done($taskId);

            return $this->success(
                message: 'Task successfuly marked as done',
                data: new TaskResource($doneTask)
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No task found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not mark a task as done', $th);

            return $this->failServerError();
        }
    }

    public function undone(int $taskId): JsonResponse
    {
        try {
            $undoneTask = $this->taskRepository->undone($taskId);

            return $this->success(
                message: 'Task successfuly marked as undone',
                data: new TaskResource($undoneTask)
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No task found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not mark a task as undone', $th);

            return $this->failServerError();
        }
    }

    public function delete(int $taskId): JsonResponse
    {
        try {
            $this->taskRepository->delete($taskId);

            return $this->success(
                message: 'Task successfuly deleted'
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No task found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not delete a task', $th);

            return $this->failServerError();
        }
    }

    public function restore(int $taskId): JsonResponse
    {
        try {
            $this->taskRepository->restore($taskId);

            return $this->success(
                message: 'Task successfuly restored'
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No task found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not restore a task', $th);

            return $this->failServerError();
        }
    }
}
