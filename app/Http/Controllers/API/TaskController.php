<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // list tasks within a project (with filtering & pagination)
    public function index(Request $request, $projectId)
    {
        $project = $request->user()->projects()->findOrFail($projectId);

        $perPage = (int) $request->get('per_page', 10);

        $query = $project->tasks()->newQuery();

        // filtering: status
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // due_date_from, due_date_to
        if ($from = $request->query('due_date_from')) {
            $query->whereDate('due_date', '>=', $from);
        }
        if ($to = $request->query('due_date_to')) {
            $query->whereDate('due_date', '<=', $to);
        }

        $tasks = $query->orderBy('due_date', 'asc')->paginate($perPage);

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request, $projectId)
    {
        $project = $request->user()->projects()->findOrFail($projectId);

        $task = $project->tasks()->create($request->validated());

        return new TaskResource($task);
    }

    public function show(Request $request, $projectId, $id)
    {
        $project = $request->user()->projects()->findOrFail($projectId);
        $task = $project->tasks()->findOrFail($id);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $projectId, $id)
    {
        $project = $request->user()->projects()->findOrFail($projectId);
        $task = $project->tasks()->findOrFail($id);

        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Request $request, $projectId, $id)
    {
        $project = $request->user()->projects()->findOrFail($projectId);
        $task = $project->tasks()->findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted'], 200);
    }
}
