<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\UserRole;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $this->authorize('viewAny', Task::class);

        $user = $request->user();

        $query = Task::query()
            ->with(['employee', 'supervisor']);

        if ($user->role === UserRole::EMPLOYEE->value) {
            $query->where('assigned_to', $user->id);
        }

        $tasks = $query->latest()->paginate(15);

        return TaskResource::collection($tasks);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {        
        
        $this->authorize('create', Task::class);

        $task = Task::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        $task->refresh()->load(['employee', 'supervisor']);
        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {               
        
         $this->authorize('view', $task);

        $task->load(['employee', 'supervisor']);

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        $task->refresh()->load(['employee', 'supervisor']);

        return new TaskResource($task);

     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();
        return response()->noContent();
    }
}
