<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Task;
use App\Models\UserInProject;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $adminProjects = Project::where('admin', '=', $user['id'])->get();
        $projects = [];
        foreach ($adminProjects as $project)
        {
            $projects[] = $project;
        }
        $userProjects = UserInProject::with('project')->where('user_id', '=', $user['id'])->get();
        foreach ($userProjects as $project)
        {
            $projects[] = $project->project;
        }
        return response() -> json($projects);
    }

    public function show($projectID): \Illuminate\Http\JsonResponse
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

    public function store(ProjectRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $project = Project::create([
           'name' => $data['name'],
           'description' => $data['description'],
           'admin' => auth()->user()['id']
        ]);
        return response() -> json($project);
    }

    public function update(ProjectRequest $request, $projectID): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $project = Project::find($projectID);
        if ($project == null)
        {
            return response() -> json(['error' => 'Project Not Found'], 404);
        }
        $project->update([
           'name' => $data['name'],
           'description' => $data['description']
        ]);
        return response() -> json($project);
    }

    public function destroy($projectID): \Illuminate\Http\JsonResponse
    {
        $project = Project::find($projectID);
        if ($project == null)
        {
            return response() -> json(['error' => 'Project Not Found'], 404);
        }
        $project->delete();
        return response() -> json('OK');
    }
}
