<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

final class TaskRepository
{
    /**
     * @return Collection<int, Task>
     */
    public function getAllActive(): Collection
    {
        return Task::query()
            ->activeTasks()
            ->get();
    }

    public function getOne(int $taskId): Task
    {
        return Task::query()->findOrFail($taskId);
    }

    public function create(string $title, ?string $description, bool $status): Task
    {
        $task = new Task();

        $this->fillModel($task, $title, $description, $status);
        $task->save();

        return $task;
    }

    public function update(Task $task, string $title, ?string $description, bool $status): Task
    {
        $this->fillModel($task, $title, $description, $status);
        $task->save();

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    private function fillModel(Task $task, string $title, ?string $description, bool $status): void
    {
        $task->title = $title;
        $task->description = $description;
        $task->status = $status;
    }
}

