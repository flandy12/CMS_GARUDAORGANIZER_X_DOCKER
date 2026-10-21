<?php

namespace App\Services;

use App\Http\Resources\AboutUs\AboutUsResource;
use App\Library\Enums\Status;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsService {
	public function showAboutUs():? AboutUsResource
	{
		$content = AboutUs::where("status", Status::ACTIVE)->first();
		
		return $content ? new AboutUsResource($content) : null;
	}

	public function uploadFile(Request $request, string $field, string $path):? string
	{
		return ($request->hasFile($field))
			? "storage/".Storage::disk('public')->put($path, $request->{$field}) : null;
	}

	public function createAboutUs(array $data): AboutUs
	{
		return AboutUs::create([
			"banner"			=> $data["banner_path"],
			"banner_wording"	=> $data["banner_wording"],
			"wording"			=> $data["wording"],
			"image"				=> $data["image_path"],
			"vision_mission"	=> $data["vision_mission"],
			"status"			=> Status::ACTIVE,
		]);
	}

	public function updateAboutUs(array $data, AboutUs $aboutUs): void
	{
		$aboutUs->update([
			"banner"			=> $data["banner_path"],
			"banner_wording"	=> $data["banner_wording"],
			"wording"			=> $data["wording"],
			"image"				=> $data["image_path"],
			"vision_mission"	=> $data["vision_mission"],
			"status"			=> Status::ACTIVE,
		]);
	}

	public function deleteAboutUs(AboutUs $aboutUs): void
	{
		$aboutUs->delete();
	}
}