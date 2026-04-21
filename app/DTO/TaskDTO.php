<?php

namespace App\DTO;

use App\Models\Task;
use JsonSerializable;

final readonly class TaskDTO implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public bool $status,
    ) {
    }

    public static function fromModel(Task $task): self
    {
        return new self(
            $task->id,
            $task->title,
            $task->description,
            $task->status,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }
}

