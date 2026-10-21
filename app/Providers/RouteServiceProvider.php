<?php

namespace App\Providers;

use App\Models\AboutUs;
use App\Models\ContactUs;
use App\Models\Home;
use App\Models\Project;
use App\Models\Service;
use App\Models\SocialMedia;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        Route::bind('home', function($value) {
            try {
                return Home::find(decrypt($value));
            } catch (\RuntimeException $e) {
                Log::info($e);

                return null;
            }
        });

        Route::bind('service', function($value) {
            try {
                return Service::find(decrypt($value));
            } catch (\RuntimeException $e) {
                Log::info($e);

                return null;
            }
        });

        Route::bind('project', function($value) {
            try {
                return Project::find(decrypt($value));
            } catch (\RuntimeException $e) {
                Log::info($e);

                return null;
            }
        });

        Route::bind('aboutUs', function($value) {
            try {
                return AboutUs::find(decrypt($value));
            } catch (\RuntimeException $e) {
                Log::info($e);

                return null;
            }
        });

        Route::bind('contactUs', function($value) {
            try {
                return ContactUs::find(decrypt($value));
            } catch (\RuntimeException $e) {
                Log::info($e);

                return null;
            }
        });

        Route::bind('socialMedia', function($value) {
            try {
                return SocialMedia::find(decrypt($value));
            } catch (\RuntimeException $e) {
                Log::info($e);

                return null;
            }
        });
    }
}
