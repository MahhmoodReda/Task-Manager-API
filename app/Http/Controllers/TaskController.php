<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddTaskRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\TaskCollection;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\ProjectUser;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Task::class,'task');
    }

    public function index()
    {
        $tasks = QueryBuilder::for(Task::class)
        ->allowedFilters(['completed'])
        ->defaultSort('-created_at')
        ->allowedSorts(['id','title','created_at'])
        ->paginate(5);
        return new TaskCollection($tasks);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function store(AddTaskRequest $request)
    {
        $validatedData = $request->validated();
        $task = Task::create($validatedData);
        ProjectUser::create([
            'user_id' => $task->user_id,
            'project_id' => $task->project_id,
        ]);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        $task->update($validatedData);
        return new TaskResource($task);
    }


    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }

}
