<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskProgressRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskStatusRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Task;
use App\Models\UserInProject;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function project($projectID): \Illuminate\Http\JsonResponse
    {
        $project = Project::with('admin')->find($projectID);
        if ($project == null)
        {
            return response() -> json(['error' => 'Project Not Found'], 404);
        }
        $project->users = UserInProject::with('user')->where('project_id', '=', $projectID)->get();
        $project->category = Category::where('project_id', '=', $projectID)->get();
        $project->tasks = Task::with('performers.user', 'category')->where('project_id', '=', (int)$projectID)->get();
        return response() -> json($project);
    }


    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $tasks = Task::where('project_id', '=', $request['project_id'])->get();
        return response() -> json($tasks);
    }

    public function show($taskID): \Illuminate\Http\JsonResponse
    {
        $task = Task::with(['category', 'performers'])->find($taskID);
        if ($task == null)
        {
            return response() -> json(['error' => 'Task Not Found'], 404);
        }
        return response() -> json($task);
    }

    public function store(TaskRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $task = Task::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'creation_date' => $data['creation_date'],
            'deadline' => $data['deadline'],
            'status' => 'Created',
            'progress' => 0,
            'is_important' => $data['is_important'],
            'project_id' => $data['project_id']
        ]);
        if ($data['category_id'] !== null || $data['category_id'] !== [])
        {
            $task->category()->attach($data['category_id']);
        }
        return $this->project($data['project_id']);
    }

    public function update(TaskUpdateRequest $request, $taskID): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $task = Task::find($taskID);
        if ($task == null)
        {
            return response() -> json(['error' => 'Task Not Found'], 404);
        }
        $task->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'deadline' => $data['deadline'],
            'status' => 'Created',
            'progress' => 0,
            'is_important' => $data['is_important'],
        ]);
        if ($data['category_id'] !== null || $data['category_id'] !== [])
        {
            $task->category()->detach();
            $task->category()->attach($data['category_id']);
        }
        return $this->project($task['project_id']);
    }

    public function status(TaskStatusRequest $request, $taskID): \Illuminate\Http\JsonResponse
    {
        $task = Task::find($taskID);
        if ($task == null)
        {
            return response()->json(['error' => 'Task Not Found'], 404);
        }
        $task->update([
            'status' => $request['status']
        ]);
        return $this->project($task['project_id']);
    }

    public function progress(TaskProgressRequest $request, $taskID): \Illuminate\Http\JsonResponse
    {
        $task = Task::find($taskID);
        if ($task == null)
        {
            return response()->json(['error' => 'Task Not Found'], 404);
        }
        $task->update([
            'progress' => $request['progress']
        ]);
        return $this->project($task['project_id']);
    }
    public function destroy($taskID): \Illuminate\Http\JsonResponse
    {
        $task = Task::find($taskID);
        if ($task == null)
        {
            return response()->json(['error' => 'Task Not Found'], 404);
        }
        $task->delete();
        return $this->project($task['project_id']);
    }
}
