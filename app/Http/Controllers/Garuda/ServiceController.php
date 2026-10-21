<?php

namespace App\Http\Controllers\Garuda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Resources\Service\ServiceResource;
use App\Library\Enums\Status;
use App\Models\Service;
use App\Services\FeatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function show(Request $request, FeatureService $featureService)
    {
    	return successResponse($featureService->showFeature($request->all()));
    }

    public function index()
    {
        $services = Service::where("status", Status::ACTIVE)->orderBy("created_at", "DESC")->get();

    	return Inertia::render("Garuda/CMS/Content/Service", [
            "services"       => ServiceResource::collection($services),
        ]);
    }

    // public function store(StoreServiceRequest $request, FeatureService $featureService)
    // {
    //     try {
    //         $featureService->setServiceData($request->validated());

    //         $featureService->createService();
    //     } catch (\Exception $e) {
    //         Log::info($e);
        
    //         return errorResponse($e);
    //     }

    //     return successResponse([], "Create Service", "Success store service in database");
    // }

    public function store(StoreServiceRequest $request, FeatureService $featureService)
    {
        try {
            $data = $request->validated();

            $banner = $featureService->uploadFile($request, "banner", "assets/service/banner");

            $image = $featureService->uploadFile($request, "service_image", "assets/service/image");

            $featureService->setOtherImage($data);

            $data["banner"] = $banner;

            $data["service_image"]  = $image;

            $featureService->createService($data);
        } catch (\Exception $e) {
            Log::info($e);
        
            return errorResponse($e);
        }

        return successResponse([], "Create Service", "Success store service in database");
    }

    public function edit(Service $service)
    {
        return successResponse(new ServiceResource($service));
    }

    public function update(UpdateServiceRequest $request, Service $service, FeatureService $featureService)
    {
        try {
            $data = $request->validated();

            $banner = is_object($request->banner) ? $featureService->uploadFile($request, "banner", "assets/service/banner") : $service->banner;

            $image = $featureService->uploadFile($request, "service_image", "assets/service/image");

            $featureService->setOtherImage($data);

            $data["banner"] = $banner;

            $data["service_image"] = $image;

            $featureService->updateService($data, $service);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse($e);
        }

        return successResponse([], "Update Service", "Success update service in database");
    }

    public function destroy(Service $service, FeatureService $featureService)
    {
        try {
            $featureService->deleteService($service);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse(500, env("INTERNAL_SERVER_ERROR_MSG"), "Internal Server Error");
        }

        return successResponse([], "Delete Service", "Success delete service in database");
    }
}
