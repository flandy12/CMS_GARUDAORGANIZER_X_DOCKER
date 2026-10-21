<?php

namespace App\Http\Controllers\Garuda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\StoreHomeRequest;
use App\Http\Requests\Home\UpdateHomeRequest;
use App\Http\Resources\Home\HomeResource;
use App\Library\Enums\Status;
use App\Models\Home;
use App\Services\HomeService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function show(HomeService $homeService)
    {
        return successResponse($homeService->showHome());
    }

    public function index()
    {
        $home = Home::where('status', Status::ACTIVE)->orderBy('created_at', 'DESC')->first();

        return Inertia::render('Garuda/CMS/Content/Home', [
            'home' => $home ? new HomeResource($home) : null,
        ]);
    }

    public function store(StoreHomeRequest $request, HomeService $homeService)
    {
        try {
            $data = $request->validated();

            $homeService->setBanner($data);

            $homeService->setService($data);

            $homeService->setSector($data);

            $homeService->setPartner($data);

            $homeService->setProject($data);

            $homeService->setAchievement($data);

            $homeService->setTestimonial($data);

            $homeService->setWhyChooseUs($data);

            $homeService->setWhyChooseUsOtherImage($data);

            $homeService->createHome();
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse(500, env('INTERNAL_SERVER_ERROR_MSG'), 'Internal Server Error');
        }

        return successResponse([], 'Create Home', 'Success store data to home database');
    }

    public function edit(Home $home)
    {
        return successResponse(new HomeResource($home));
    }

    public function update(UpdateHomeRequest $request, Home $home, HomeService $homeService)
    {
        try {
            $data = $request->validated();

            $homeService->setBanner($data);

            $homeService->setService($data);

            $homeService->setSector($data);

            $homeService->setPartner($data);

            $homeService->setProject($data);

            $homeService->setAchievement($data);

            $homeService->setTestimonial($data);

            $homeService->setWhyChooseUs($data);

            $homeService->setWhyChooseUsOtherImage($data);

            $homeService->updateHome($home);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse(500, env('INTERNAL_SERVER_ERROR_MSG', 'Internal Server Error'));
        }

        return successResponse([], 'Update Home', 'Success update data to home database');
    }

    public function destroy(Home $home, HomeService $homeService)
    {
        try {
            $homeService->deleteHome($home);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse(500, env('INTERNAL_SERVER_ERROR_MSG'), 'Internal Server Error');
        }

        return successResponse([], 'Delete Home', 'Success delete home');
    }
}
