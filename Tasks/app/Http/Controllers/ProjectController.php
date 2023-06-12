<?php

namespace App\Http\Controllers;

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

    public function show($projectID)
    {
        $project = Project::with('admin')->find($projectID);
        $project->users = UserInProject::with('user')->where('project_id', '=', $projectID)->get();
        $project->tasks = Task::with('performers.user', 'category')->where('project_id', '=', (int)$projectID)->get();
        return response() -> json($project);
    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
