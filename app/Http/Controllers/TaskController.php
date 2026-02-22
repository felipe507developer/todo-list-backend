<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateItemStatusRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Item;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::query()
            ->with('items')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $tasks]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $items = $payload['items'] ?? [];
        unset($payload['items']);

        if (($payload['status'] ?? Task::STATUS_PENDING) === Task::STATUS_DONE && $this->hasIncompleteItems($items)) {
            return response()->json([
                'message' => 'Task cannot be marked as done while it has incomplete items',
            ], 422);
        }

        $task = DB::transaction(function () use ($payload, $items) {
            $task = Task::create($payload);

            if (! empty($items)) {
                $task->items()->createMany($this->normalizeItems($items));
            }

            return $task;
        });

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task->load('items'),
        ], 201);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $payload = $request->validated();
        $items = $payload['items'] ?? null;
        unset($payload['items']);

        $willBeDone = ($payload['status'] ?? $task->status) === Task::STATUS_DONE;
        $hasIncompleteItems = $items !== null
            ? $this->hasIncompleteItems($items)
            : $task->items()->where('is_completed', false)->exists();

        if ($willBeDone && $hasIncompleteItems) {
            return response()->json([
                'message' => 'Task cannot be marked as done while it has incomplete items',
            ], 422);
        }

        DB::transaction(function () use ($task, $payload, $items) {
            $task->update($payload);

            if ($items !== null) {
                $task->items()->delete();
                if (! empty($items)) {
                    $task->items()->createMany($this->normalizeItems($items));
                }
            }
        });

        return response()->json([
            'message' => 'Task updated successfully',
            'data' => $task->load('items'),
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(null, 204);
    }

    public function updateItemStatus(UpdateItemStatusRequest $request, Task $task, Item $item): JsonResponse
    {
        if ($item->task_id !== $task->id) {
            return response()->json([
                'message' => 'Item does not belong to the specified task',
            ], 422);
        }

        DB::transaction(function () use ($request, $task, $item) {
            $item->update($request->validated());

            if ($task->status === Task::STATUS_DONE && $task->items()->where('is_completed', false)->exists()) {
                $task->update(['status' => Task::STATUS_IN_PROGRESS]);
            }
        });

        return response()->json([
            'message' => 'Item status updated successfully',
            'data' => $task->load('items'),
        ]);
    }

    private function hasIncompleteItems(array $items): bool
    {
        foreach ($items as $item) {
            if (($item['is_completed'] ?? false) === false) {
                return true;
            }
        }

        return false;
    }

    private function normalizeItems(array $items): array
    {
        return array_map(function (array $item) {
            return [
                'title' => $item['title'],
                'is_completed' => $item['is_completed'] ?? false,
                'priority' => $item['priority'] ?? 'medium',
            ];
        }, $items);
    }
}
