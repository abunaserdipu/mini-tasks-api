<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);

        $projects = $request->user()
            ->projects()
            ->withCount('tasks')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $request->user()->projects()->create($request->validated());

        return new ProjectResource($project->load('tasks'));
    }

    public function show(Request $request, $id)
    {
        $project = $request->user()->projects()->with('tasks')->findOrFail($id);

        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $project = $request->user()->projects()->findOrFail($id);
        $project->update($request->validated());

        return new ProjectResource($project);
    }

    public function destroy(Request $request, $id)
    {
        $project = $request->user()->projects()->findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project deleted'], 200);
    }
}
