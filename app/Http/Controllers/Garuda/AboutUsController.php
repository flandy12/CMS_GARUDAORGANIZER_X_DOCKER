<?php

namespace App\Http\Controllers\Garuda;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutUs\StoreAboutUsRequest;
use App\Http\Requests\AboutUs\UpdateAboutUsRequest;
use App\Http\Resources\AboutUs\AboutUsResource;
use App\Library\Enums\Status;
use App\Models\AboutUs;
use App\Services\AboutUsService;
use Inertia\Inertia;

class AboutUsController extends Controller
{
    public function show(AboutUsService $aboutUsService)
    {
    	return successResponse($aboutUsService->showAboutUs());
    }

    public function index()
    {
    	$about_us = AboutUs::where("status", Status::ACTIVE)->orderBy("created_at", "DESC")->first();

    	return Inertia::render("Garuda/CMS/Content/AboutUs", [
    		"aboutUs"		=> $about_us ? new AboutUsResource($about_us) : null,
    	]);
    }

    public function store(StoreAboutUsRequest $request, AboutUsService $aboutUsService)
    {
        try {
            $data = $request->validated();

            $banner_path = $aboutUsService->uploadFile($request, "banner", "assets/about-us/banner");

            $image_path = $aboutUsService->uploadFile($request, "image", "assets/about-us/image");

            $aboutUsService->createAboutUs($data+["banner_path" => $banner_path, "image_path" => $image_path]);
        } catch (\Exception $e) {
            Log::info($e);
        
            return errorResponse($e);
        }

        return successResponse([], "Create About Us", "Success store about us in database");
    }

    public function edit(AboutUs $aboutUs)
    {
        return successResponse(new AboutUsResource($aboutUs));
    }

    public function update(UpdateAboutUsRequest $request, AboutUs $aboutUs, AboutUsService $aboutUsService)
    {
        try {
            $data = $request->validated();

            $banner_path = is_file(asset($request->banner)) ? $request->banner : $aboutUsService->uploadFile($request, "banner", "assets/about-us/banner");

            $image_path = is_file(asset($request->image)) ? $request->image : $aboutUsService->uploadFile($request, "image", "asset/about-us/image");

            $aboutUsService->updateAboutUs($data+["banner_path" => $banner_path, "image_path" => $image_path], $aboutUs);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse($e);
        }

        return successResponse([], "Update About Us", "Success update about us in database");
    }

    public function destroy(AboutUs $aboutUs, AboutUsService $aboutUsService)
    {
        try {
            $aboutUsService->deleteAboutUs($aboutUs);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse(500, env("INTERNAL_SERVER_ERROR_MSG"), "Internal Server Error");
        }

        return successResponse([], "Delete About Us", "Success delete about us in database");
    }
}
