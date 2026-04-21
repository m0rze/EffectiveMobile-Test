<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskUpsertRequest;
use App\DTO\TaskDTO;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class TaskController extends Controller
{
    public function __construct(private readonly TaskRepository $taskRepository)
    {
    }

    public function index(): JsonResponse
    {
        $tasks = $this->taskRepository->getAllActive();

        return response()->json($tasks->map(static fn ($task) => TaskDTO::fromModel($task))->all());
    }

    public function show(int $task): JsonResponse
    {
        $model = $this->taskRepository->getOne($task);

        if (! $model->status) {
            abort(404);
        }

        return response()->json(TaskDTO::fromModel($model));
    }

    public function store(TaskUpsertRequest $request): JsonResponse
    {
        $task = $this->taskRepository->create(
            $request->validated('title'),
            $request->validated('description'),
            $request->boolean('status'),
        );

        return response()->json(TaskDTO::fromModel($task));
    }

    public function update(TaskUpsertRequest $request, int $task): JsonResponse
    {
        $model = $this->taskRepository->getOne($task);

        $updated = $this->taskRepository->update(
            $model,
            $request->validated('title'),
            $request->validated('description'),
            $request->boolean('status'),
        );

        return response()->json(TaskDTO::fromModel($updated));
    }

    public function destroy(int $task): Response
    {
        $model = $this->taskRepository->getOne($task);

        $this->taskRepository->delete($model);

        return response()->noContent();
    }
}

