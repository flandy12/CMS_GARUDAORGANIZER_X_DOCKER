<?php

namespace App\Services;

use App\Http\Resources\Home\HomeResource;
use App\Library\Enums\Status;
use App\Models\Home;

class HomeService {
	protected $banners = [];
	protected $services = [];
	protected $sectors = [];
	protected $partners = [];
	protected $projects = [];
	protected $achievements = [];
	protected $testimonials = [];
	protected $why_choose_us = [];
	protected $why_choose_us_other_images = [];

	public function showHome():? HomeResource
	{
		$content = Home::where("status", Status::ACTIVE)->first();

		return $content ? new HomeResource($content) : null;
	}

	public function setBanner(array $data): void
	{
		foreach ($data["banner_images"] as $key => $image) {
			$image_path = $this->uploadArrayImage($image, "public/assets/home/banner");

			$this->banners[] = [
				"image"				=> $image_path,
				"title"				=> $data["banner_titles"][$key],
				"description"		=> $data["banner_descriptions"][$key],
				"background_color"	=> $data["banner_colors"][$key],
			];
		}
	}

	public function setService(array $data): void
	{
		foreach ($data["service_name"] as $key => $name) {
			$image_path = $this->uploadArrayImage($data["service_images"][$key], "public/assets/home/service");

			$this->services[] = [
				"name"			=> $name,
				"link"			=> $data["service_links"][$key],
				"description"	=> $data["service_descriptions"][$key],
				"image"			=> $image_path,
			];
		}
	}

	public function setSector(array $data): void
	{
		foreach ($data["sector_name"] as $key => $name) {
			$image_path = $this->uploadArrayImage($data["sector_images"][$key], "public/assets/home/sector");

			$this->sectors[] = [
				"name"			=> $name,
				"description"	=> $data["sector_descriptions"][$key],
				"image"			=> $image_path,
			];
		}
	}

	public function setPartner(array $data): void
	{
		foreach ($data["partner_logo"] as $key => $logo) {
			$image_path = $this->uploadArrayImage($logo, "public/assets/home/partner");

			$this->partners[] = [
				"logo"			=> $image_path,
			];
		}
	}

	public function setProject(array $data): void
	{
		foreach ($data["project_name"] as $key => $name) {
			$image_path = $this->uploadArrayImage($data["project_images"][$key], "public/assets/home/project");

			$this->projects[] = [
				"name"			=> $name,
				"description"	=> $data["project_descriptions"][$key],
				"image"			=> $image_path,
			];
		}
	}

	public function setAchievement(array $data): void
	{
		foreach ($data["achievement_images"] as $key => $image) {
			$image_path = $this->uploadArrayImage($image, "public/assets/home/achievement");
			
			$this->achievements[] = [
				"image"				=> $image_path,
				"number"			=> $data["achievement_numbers"][$key],
				"name"				=> $data["achievement_name"][$key],
			];
		}
	}

	public function setTestimonial(array $data): void
	{
		foreach ($data["testimonial_titles"] as $key => $title) {
			if(isset($data["testimonial_images"][$key])) {
				$image_path = $this->uploadArrayImage($data["testimonial_images"][$key], "public/assets/home/testimonial");

				$this->testimonials[] = [
					"title"				=> $title,
					"image"				=> $image_path,
					"video"				=> $data["testimonial_videos"][$key],
				];
			}
		}
	}

	public function setWhyChooseUs(array $data): void
	{
		foreach ($data["why_choose_us_descriptions"] as $key => $description) {
			$image_path = $this->uploadArrayImage($data["why_choose_us_images"][$key], "public/assets/home/why-choose-us");

			$this->why_choose_us[] = [
				"description"		=> $description,
				"image"				=> $image_path,
			];
		}
	}

	public function setWhyChooseUsOtherImage(array $data): void
	{
		foreach ($data["why_choose_us_other_images"] as $key => $image) {
			$image_path = $this->uploadArrayImage($data["why_choose_us_other_images"][$key], "public/assets/why_choose_us/other-image");

			$this->why_choose_us_other_images[] = $image_path;
		}
	}

	public function createHome(): Home
	{
		return Home::create([
			"banner"						=> $this->banners,
			"service"						=> $this->services,
			"sector"						=> $this->sectors,
			"partner"						=> $this->partners,
			"project"						=> $this->projects,
			"achievement"					=> $this->achievements,
			"testimonial"					=> $this->testimonials,
			"why_choose_us"					=> $this->why_choose_us,
			"why_choose_us_other_image"		=> $this->why_choose_us_other_images,
			"status"						=> Status::ACTIVE,
		]);
	}

	public function updateHome(Home $home, bool $status = Status::ACTIVE): void
	{
		$home->update([
			"banner"						=> $this->banners,
			"service"						=> $this->services,
			"sector"						=> $this->sectors,
			"partner"						=> $this->partners,
			"project"						=> $this->projects,
			"achievement"					=> $this->achievements,
			"testimonial"					=> $this->testimonials,
			"why_choose_us"					=> $this->why_choose_us,
			"why_choose_us_other_image"		=> $this->why_choose_us_other_images,
			"status"						=> $status,
		]);
	}

	public function deleteHome(Home $home): void
	{
		$home->delete();
	}

	protected function uploadArrayImage($file, string $filePath):? string
	{
		if(is_object($file)) {
			$fileName = uniqid() . '.' . $file->getClientOriginalExtension() ?: 'png';

			$path = $file->storeAs($filePath, $fileName);

			return str_replace("public", "storage", $path);
		}

		return $file;
	}
}