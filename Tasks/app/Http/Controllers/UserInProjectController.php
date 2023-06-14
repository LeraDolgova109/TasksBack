<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Task;
use App\Models\UserInProject;
use Illuminate\Http\Request;

class UserInProjectController extends Controller
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
        $users = UserInProject::where('project_id', '=', $request['project_id'])->get();
        return response() -> json($users);
    }

    public function show(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = UserInProject::where([
            ['user_id', '=', $request['user_id']],
            ['project_id', '=', $request['project_id']]
        ]);
        if ($users == null)
        {
            return response() -> json(['error' => 'User Not Found'], 404);
        }
        return response() -> json($users);
    }

    public function store(UserProjectRequest $request)
    {
        $data = $request->validated();
        UserInProject::create([
           'user_id' => $data['user_id'],
           'project_id' => $data['project_id'],
           'is_moderator' => $data['']
        ]);
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
