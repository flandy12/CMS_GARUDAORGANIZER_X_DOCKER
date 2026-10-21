<?php

namespace App\Services;

use App\Http\Resources\Service\FeatureResource;
use App\Library\Enums\Status;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeatureService {
	protected $services = [];
	protected $other_images = [];

	public function showFeature(array $data):? FeatureResource
	{
		$content = Service::where("type", $data["url"])->where("status", Status::ACTIVE)->first();

		return $content ? new FeatureResource($content) : null;
	}

	public function setServiceData(array $data): void
	{
		foreach ($data["wording"] as $key => $wording) {
			$banner = $this->uploadArrayImage($data["banner"][$key], "public/assets/service/banner");
			
			$this->services[] = [
				"type"			=> $this->typeFormat($data["type"][$key]),
				"banner"		=> $banner,
				"wording"		=> $wording,
				"description"	=> $data["service_descriptions"][$key],
			];
		}
	}

	public function setOtherImage(array $data): void
	{
		if(isset($data["other_images"])) {
			foreach ($data["other_images"] as $key => $value) {
				$other_image = $data["other_images"][$key];

				if(is_object($other_image)) {
					$image = $this->uploadArrayImage($data["other_images"][$key], "public/assets/service/other-images");
				} else {
					$image = $other_image;
				}
				
				$this->other_images[] = [
					"image"		=> $image,
					"title"		=> $data["other_image_titles"][$key],
				];
			}
		}
	}

	// public function createService(): void
	// {
	// 	foreach ($this->services as $key => $service) {
	// 		Service::create([
	// 			"type"				=> $service['type'],
	// 			"banner"			=> $service['banner'],
	// 			"wording"			=> $service['wording'],
	// 			"description"		=> $service['description'],
	// 			"status"			=> Status::ACTIVE,
	// 		]);
	// 	}
	// }

	public function createService(array $data): Service
	{
		return Service::create([
			"type"			=> $this->typeFormat($data["type"]),
			"banner"		=> $data["banner"],
			"wording"		=> $data["wording"],
			"title"			=> $data["service_title"],
			"description"	=> $data["service_description"],
			"image"			=> $data["service_image"],
			"other_image"	=> $this->other_images,
			"status"		=> Status::ACTIVE,
		]);
	}

	public function updateService(array $data, Service $model): void
	{
		$model->update([
			"type"			=> $this->typeFormat($data["type"]),
			"banner"		=> $data["banner"],
			"wording"		=> $data["wording"],
			"title"			=> $data["service_title"],
			"description"	=> $data["service_description"],
			"image"			=> $data["service_image"],
			"other_image"	=> $this->other_images,
			"status"		=> Status::ACTIVE,
		]);
	}

	protected function typeFormat($type): string
	{
		$string = str_replace(' ', '-', strtolower($type));

   		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
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

	public function deleteService(Service $service): void
	{
		$service->delete();
	}

	public function uploadFile(Request $request, string $field, string $path):? string
	{
		return ($request->hasFile($field))
			? "storage/".Storage::disk('public')->put($path, $request->{$field}) : null;
	}
}