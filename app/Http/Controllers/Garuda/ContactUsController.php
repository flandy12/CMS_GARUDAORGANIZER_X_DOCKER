<?php

namespace App\Http\Controllers\Garuda;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUs\StoreContactUsMessageRequest;
use App\Http\Requests\ContactUs\StoreContactUsRequest;
use App\Http\Requests\ContactUs\UpdateContactUsRequest;
use App\Http\Resources\ContactUs\ContactUsResource;
use App\Library\Enums\Status;
use App\Models\ContactUs;
use App\Services\ContactUsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ContactUsController extends Controller
{
    public function index()
    {
    	$contact_us = ContactUs::where("status", Status::ACTIVE)->orderBy("created_at", "DESC")->first();

    	return Inertia::render("Garuda/CMS/Content/ContactUs", [
    		"contactUs"		=> $contact_us ? new ContactUsResource($contact_us) : null,
    	]);
    }

    public function store(StoreContactUsRequest $request, ContactUsService $contactUsService)
    {
        try {
            $contactUsService->createContactUs($request->validated());
        } catch (\Exception $e) {
            Log::info($e);
        
            return errorResponse($e);
        }

        return successResponse([], "Create Contact Us", "Success store contact us in database");
    }

    public function edit(ContactUs $contactUs)
    {
        return successResponse(new ContactUsResource($contactUs));
    }

    public function update(UpdateContactUsRequest $request, ContactUs $contactUs, ContactUsService $contactUsService)
    {
        try {
            $contactUsService->updateContactUs($request->validated(), $contactUs);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse($e);
        }

        return successResponse([], "Update Contact Us", "Success update contact us in database");
    }

    public function destroy(ContactUs $contactUs, ContactUsService $contactUsService)
    {
        try {
            $contactUsService->deleteContactUs($contactUs);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse(500, env("INTERNAL_SERVER_ERROR_MSG"), "Internal Server Error");
        }

        return successResponse([], "Delete Contact Us", "Success delete contact us in database");
    }

    public function sendMessage(StoreContactUsMessageRequest $request, ContactUsService $contactUsService)
    {
        try {
            $data = $request->validated();

            $contact_us = ContactUs::where("status", Status::ACTIVE)->orderBy("created_at", "DESC")->first();

            $contactUsService->createMessage($data, $contact_us->to_email);
            
            $contactUsService->sendEmail($data, $contact_us->to_email);
        } catch (\Exception $e) {
            Log::info($e);

            return errorResponse(500, env("INTERNAL_SERVER_ERROR_MSG"), "Internal Server Error");
        }

        return successResponse([], "Send Message to GarudaOrganizer Team", "Success send message to our team. Please wait 1x24 hours, we will reply you as soon as possible");
    }

    public function information(ContactUsService $contactUsService)
    {
        try {
            $contact_us = $contactUsService->getInformation();
        } catch (\Exception $e) {
            Log::info($e);
            
            return errorResponse(500, env("INTERNAL_SERVER_ERROR_MSG"), "Internal Server Error");
        }

        return successResponse($contact_us);
    }
}
