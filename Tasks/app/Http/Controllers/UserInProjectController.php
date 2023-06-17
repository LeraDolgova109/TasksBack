<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProjectRequest;
use App\Http\Requests\UserProjectUpdateRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
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
    public function index(): \Illuminate\Http\JsonResponse
    {
        $users = User::select('email','nickname')->get();
        return response() -> json($users);
    }

    public function show(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = UserInProject::where([
            ['user_id', '=', $request['user_id']],
            ['project_id', '=', $request['project_id']]
        ]);
        if ($user == null)
        {
            return response() -> json(['error' => 'User Not Found'], 404);
        }
        return response() -> json($user);
    }

    public function invitations(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $userInProject = UserInProject::where([
            ['user_id', '=', $user['id']],
            ['accepted', '=', false]
        ])->get();
        return response() -> json($userInProject);
    }

    public function store(UserProjectRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = User::where('email', '=', $data['email']);
        UserInProject::create([
            'user_id' => $user['id'],
            'project_id' => $data['project_id'],
            'is_moderator' => $data['is_moderator'],
            'accepted' => false
        ]);
        return $this->project($data['project_id']);
    }

    public function update(UserProjectUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = UserInProject::where([
            ['user_id', '=', $request['user_id']],
            ['project_id', '=', $request['project_id']]
        ]);
        $user->update([
            'is_moderator' => $data['is_moderator'],
            'accepted' => $data['accepted']
        ]);
        return $this->project($data['project_id']);
    }

    public function accept(UserProjectUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = UserInProject::where([
            ['user_id', '=', $request['user_id']],
            ['project_id', '=', $request['project_id']]
        ]);
        if ($data['accept'])
        {
            $user->update([
                'accepted' => $data['accept']
            ]);
        }
        else
        {
            $user->delete();
        }
        return $this->invitations();
    }

    public function destroy(UserProjectUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = UserInProject::where([
            ['user_id', '=', $request['user_id']],
            ['project_id', '=', $request['project_id']]
        ]);
        $user->delete();
        return $this->project($data['project_id']);
    }
}
