<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_only_active_tasks(): void
    {
        $active = Task::factory()->create(['status' => true]);
        Task::factory()->create(['status' => false]);

        $response = $this->getJson('/api/tasks');

        $response
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $active->id]);
    }

    public function test_show_returns_404_for_inactive_task(): void
    {
        $inactive = Task::factory()->create(['status' => false]);

        $this->getJson("/api/tasks/{$inactive->id}")
            ->assertNotFound();
    }

    public function test_store_creates_task(): void
    {
        $payload = [
            'title' => 'My task',
            'description' => null,
            'status' => true,
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'title' => 'My task',
                'description' => null,
                'status' => true,
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'My task',
            'description' => null,
            'status' => 1,
        ]);
    }

    public function test_update_updates_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Old',
            'description' => 'Old desc',
            'status' => true,
        ]);

        $payload = [
            'title' => 'New',
            'description' => 'New desc',
            'status' => false,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $task->id,
                'title' => 'New',
                'description' => 'New desc',
                'status' => false,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'New',
            'description' => 'New desc',
            'status' => 0,
        ]);
    }

    public function test_destroy_deletes_task(): void
    {
        $task = Task::factory()->create();

        $this->deleteJson("/api/tasks/{$task->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}

