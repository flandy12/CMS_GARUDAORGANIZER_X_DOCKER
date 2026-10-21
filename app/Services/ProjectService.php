<?php

namespace App\Services;

use App\Http\Resources\Project\ProjectResource;
use App\Library\Enums\Status;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectService {
	protected $projects = [];

	public function showProject():? ProjectResource
	{
		$content = Project::where("status", Status::ACTIVE)->first();

		return $content ? new ProjectResource($content) : null;
	}

	public function setProjectData(array $data): void
	{
		foreach ($data["project_images"] as $key => $image) {
			$image_path = $this->uploadArrayImage($data["project_images"][$key], "public/assets/project/image");

			$this->projects[] = [
				"title"			=> $data["project_titles"][$key],
				"description"	=> $data["project_descriptions"][$key],
				"image"			=> $image_path
			];
		}		
	}

	public function uploadFile(Request $request, string $field, string $path):? string
	{
		return ($request->hasFile($field))
			? "storage/".Storage::disk('public')->put($path, $request->{$field}) : null;
	}

	public function createProject(array $data): Project
	{
		return Project::create([
			"banner"			=> $data["banner_path"],
			"wording"			=> isset($data["wording"]) ? $data["wording"] : null,
			"results"			=> $this->projects,
			"status"			=> Status::ACTIVE,
		]);
	}

	public function updateProject(array $data, Project $project): void
	{
		$project->update([
			"banner"			=> $data["banner_path"],
			"wording"			=> isset($data["wording"]) ? $data["wording"] : null,
			"results"			=> $this->projects,
			"status"			=> Status::ACTIVE,
		]);
	}

	protected function uploadArrayImage($file, string $filePath): string
	{
		if(is_object($file)) {
			$fileName = uniqid() . '.' . $file->getClientOriginalExtension() ?: 'png';

			$path = $file->storeAs($filePath, $fileName);

			return str_replace("public", "storage", $path);
		}

		return $file;
	}

	public function deleteProject(Project $project): void
	{
		$project->delete();
	}
}