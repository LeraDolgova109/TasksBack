<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Task;
use App\Models\UserInProject;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
        $category = Category::where('project_id', '=', $request['project_id'])->get();
        return response() -> json($category);
    }

    public function show($categoryID): \Illuminate\Http\JsonResponse
    {
        $category = Category::find($categoryID);
        if ($category == null)
        {
            return response() -> json(['error' => 'Category Not Found'], 404);
        }
        return response() -> json($category);
    }

    public function store(CategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $category = Category::create($data);
        return $this->project($data['project_id']);
    }

    public function update(CategoryUpdateRequest $request, $categoryID): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $category = Category::find($categoryID);
        if ($category == null)
        {
            return response() -> json(['error' => 'Category Not Found'], 404);
        }
        $category->update([
            'name' => $data['name']
        ]);
        return $this->project($category['project_id']);
    }

    public function destroy($categoryID): \Illuminate\Http\JsonResponse
    {
        $category = Category::find($categoryID);
        $projectID = $category['projectID'];
        if ($category == null)
        {
            return response() -> json(['error' => 'Category Not Found'], 404);
        }
        $category->delete();
        return $this->project($projectID);
    }
}
