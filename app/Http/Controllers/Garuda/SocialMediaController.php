<?php

namespace App\Http\Controllers\Garuda;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialMedia\StoreSocialMediaRequest;
use App\Http\Requests\SocialMedia\UpdateSocialMediaRequest;
use App\Models\SocialMedia;
use App\Services\SocialMediaService;
use Inertia\Inertia;

class SocialMediaController extends Controller
{
    public function index()
    {
    	return Inertia::render('Garuda/CMS/Content/SocialMedia');
    }

    public function store(StoreSocialMediaRequest $request, SocialMediaService $socialMediaService)
    {

    }

    public function update(UpdateSocialMediaRequest $request, SocialMedia $socialMedia, SocialMediaService $socialMediaService)
    {

    }

    public function destroy(SocialMedia $socialMedia, SocialMediaService $socialMediaService)
    {
    	
    }
}
