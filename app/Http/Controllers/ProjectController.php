<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project'); // authorizes all methods for the project model.
    }

    public function index()
    {
        $projects = QueryBuilder::for(Project::class)
        ->allowedIncludes('tasks')
        ->allowedFilters(['name'])
        ->defaultSort('-id')
        ->allowedSorts(['name','id','created_at'])
        ->paginate(5);
        return new ProjectCollection($projects);
    }


    public function store(AddProjectRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id();
        $project= Project::create($validatedData);
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        // if ($project->user_id !== Auth::id()) {
        //     return response()->json([
        //         'message' => 'invalid project'
        //     ],403);
        //     }
        $validatedData = $request->validated();

        $project ->update($validatedData);
        return new ProjectResource($project);
    }


    public function show(Project $project)
    {
        return (new ProjectResource($project))
                ->load('tasks')
                ->load('users');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->noContent();

    }
}
