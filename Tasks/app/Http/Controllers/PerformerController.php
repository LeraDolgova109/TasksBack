<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerformerRequest;
use App\Models\Category;
use App\Models\Performer;
use App\Models\Project;
use App\Models\Task;
use App\Models\UserInProject;
use Illuminate\Http\Request;

class PerformerController extends Controller
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

    public function index()
    {

    }

    public function show()
    {

    }

    public function store(PerformerRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $task = Task::where('id', '=', $data['task_id']);
        Performer::create([
            'task_id' => $data['task_id'],
            'user_id' => $data['user_id'],
        ]);
        return $this->project($task['project_id']);
    }

    public function update()
    {

    }

    public function delete(PerformerRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $task = Task::where('id', '=', $data['task_id']);
        Performer::create([
            'task_id' => $data['task_id'],
            'user_id' => $data['user_id'],
        ]);
        return $this->project($task['project_id']);
    }
}
