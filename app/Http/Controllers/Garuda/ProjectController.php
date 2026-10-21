<?php

namespace App\Http\Controllers\Garuda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Library\Enums\Status;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function show(ProjectService $projectService)
    {
    	return successResponse($projectService->showProject());
    }

    public function index()
    {
    	$project = Project::where("status", Status::ACTIVE)->orderBy("created_at", "DESC")->first();

    	return Inertia::render("Garuda/CMS/Content/Project", [
    		"project"		=> $project ? new ProjectResource($project) : null,
    	]);
    }

    public function store(StoreProjectRequest $request, ProjectService $projectService)
    {
        try {
            $data = $request->validated();

            $projectService->setProjectData($data);

            $banner_path = $projectService->uploadFile($request, "banner", "assets/project/banner");

            $projectService->createProject($data+["banner_path" => $banner_path]);
        } catch (\Exception $e) {
            Log::info($e);
        
            return errorResponse($e);
        }

        return successResponse([], "Create Project", "Success store project in database");
    }

    public function edit(Project $project)
    {
        return successResponse(new ProjectResource($project));
    }

    public function update(UpdateProjectRequest $request, Project $project, ProjectService $projectService)
    {
        try {
            $data = $request->validated();

            $projectService->setProjectData($data);

            $banner_path = is_file(asset($request->banner)) ? $projectService->uploadFile($request, "banner", "assets/project/banner") : $request->banner;

            $projectService->updateProject($data+["banner_path" => $banner_path], $project);

            Log::info($data);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse($e);
        }

        return successResponse([], "Update Project", "Success update project in database");
    }

    public function destroy(Project $project, ProjectService $projectService)
    {
        try {
            $projectService->deleteProject($project);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse(500, env("INTERNAL_SERVER_ERROR_MSG"), "Internal Server Error");
        }

        return successResponse([], "Delete Project", "Success delete project in database");
    }
}
