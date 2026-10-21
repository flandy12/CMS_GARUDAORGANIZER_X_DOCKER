<?php

use App\Http\Controllers\DashboardController;
use App\Models\JobfairRegistrations;
use App\Models\NewRegisters;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return redirect()->route("login");
    return Inertia::render('Auth/Register');

});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/home', [\App\Http\Controllers\Garuda\HomeController::class, 'index'])->name('home')->middleware('permission:view home');

    Route::post('/home/store', [\App\Http\Controllers\Garuda\HomeController::class, 'store'])->name('home.store')->middleware('permission:create home');

    Route::get('/home/edit/{home}', [\App\Http\Controllers\Garuda\HomeController::class, 'edit'])->name('home.edit')->middleware('permission:update home');

    Route::put('/home/update/{home}', [\App\Http\Controllers\Garuda\HomeController::class, 'update'])->name('home.update')->middleware('permission:update home');

    Route::delete('/home/delete/{home}', [\App\Http\Controllers\Garuda\HomeController::class, 'destroy'])->name('home.delete')->middleware('permission:delete home');

    Route::get('/service', [\App\Http\Controllers\Garuda\ServiceController::class, 'index'])->name('service')->middleware('permission:view service');

    Route::post('/service/store', [\App\Http\Controllers\Garuda\ServiceController::class, 'store'])->name('service.store')->middleware('permission:create service');

    Route::get('/service/edit/{service}', [\App\Http\Controllers\Garuda\ServiceController::class, 'edit'])->name('service.edit')->middleware('permission:update service');

    Route::put('/service/update/{service}', [\App\Http\Controllers\Garuda\ServiceController::class, 'update'])->name('service.update')->middleware('permission:update service');

    Route::delete('/service/delete/{service}', [\App\Http\Controllers\Garuda\ServiceController::class, 'destroy'])->name('service.delete')->middleware('permission:delete service');

    Route::get('/project', [\App\Http\Controllers\Garuda\ProjectController::class, 'index'])->name('project')->middleware('permission:view project');

    Route::post('/project/store', [\App\Http\Controllers\Garuda\ProjectController::class, 'store'])->name('project.store')->middleware('permission:create project');

    Route::get('/project/edit/{project}', [\App\Http\Controllers\Garuda\ProjectController::class, 'edit'])->name('project.edit')->middleware('permission:update project');

    Route::put('/project/update/{project}', [\App\Http\Controllers\Garuda\ProjectController::class, 'update'])->name('project.update')->middleware('permission:update project');

    Route::delete('/project/delete/{project}', [\App\Http\Controllers\Garuda\ProjectController::class, 'destroy'])->name('project.delete')->middleware('permission:delete project');

    Route::get('/about-us', [\App\Http\Controllers\Garuda\AboutUsController::class, 'index'])->name('about_us')->middleware('permission:view about us');

    Route::post('/about-us/store', [\App\Http\Controllers\Garuda\AboutUsController::class, 'store'])->name('about_us.store')->middleware('permission:create about us');

    Route::get('/about-us/edit/{aboutUs}', [\App\Http\Controllers\Garuda\AboutUsController::class, 'edit'])->name('about_us.edit')->middleware('permission:update about us');

    Route::put('/about-us/update/{aboutUs}', [\App\Http\Controllers\Garuda\AboutUsController::class, 'update'])->name('about_us.update')->middleware('permission:update about us');

    Route::delete('/about-us/delete/{aboutUs}', [\App\Http\Controllers\Garuda\AboutUsController::class, 'destroy'])->name('about_us.delete')->middleware('permission:delete about us');

    Route::get('/contact-us', [\App\Http\Controllers\Garuda\ContactUsController::class, 'index'])->name('contact_us')->middleware('permission:view contact us');

    Route::post('/contact-us/store', [\App\Http\Controllers\Garuda\ContactUsController::class, 'store'])->name('contact_us.store')->middleware('permission:create contact us');

    Route::get('/contact-us/edit/{contactUs}', [\App\Http\Controllers\Garuda\ContactUsController::class, 'edit'])->name('contact_us.edit')->middleware('permission:update contact us');

    Route::put('/contact-us/update/{contactUs}', [\App\Http\Controllers\Garuda\ContactUsController::class, 'update'])->name('contact_us.update')->middleware('permission:update contact us');

    Route::delete('/contact-us/delete/{contactUs}', [\App\Http\Controllers\Garuda\ContactUsController::class, 'destroy'])->name('contact_us.delete')->middleware('permission:delete contact us');

    Route::get('/social-media', [\App\Http\Controllers\Garuda\SocialMediaController::class, 'index'])->name('social_media')->middleware('permission:view social media');

    Route::post('/social-media/store', [\App\Http\Controllers\Garuda\SocialMediaController::class, 'store'])->name('social_media.store')->middleware('permission:create social media');

    Route::get('/social-media/edit/{socialMedia}', [\App\Http\Controllers\Garuda\SocialMediaController::class, 'edit'])->name('social_media.edit')->middleware('permission:update social media');

    Route::put('/social-media/update/{socialMedia}', [\App\Http\Controllers\Garuda\SocialMediaController::class, 'update'])->name('social_media.update')->middleware('permission:update social media');

    Route::delete('/social-media/delete/{socialMedia}', [\App\Http\Controllers\Garuda\SocialMediaController::class, 'destroy'])->name('social_media.delete')->middleware('permission:delete social media');
});

Route::post('/job-fair-registrations', function (Request $request) {
    // Validate the request data
    $validatedData = $request->validate([
        'email' => 'required|email',
        'nama_lengkap' => 'required|string|max:255',
        'tanggal_acara' => 'required|date',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:Pria,Wanita',
        'nomor_hp' => 'required|string|max:15',
        'nik' => 'required|string|max:16',
        'alamat_domisili' => 'required|string|max:255',
        'kota_domisili' => 'required|string|max:100',
        'pendidikan_akhir' => 'required|string|max:100',
        'nama_sekolah' => 'nullable|string|max:255',
        'pengalaman_kerja_terakhir' => 'nullable|string|max:255',
        'pelatihan_habitat' => 'required|in:Iya,Tidak',
        'pelatihan_lain' => 'required|in:Iya,Tidak',
        'nama_lembaga_pelatihan' => 'nullable|string|max:255',
        'form_komentar' => 'nullable|string|max:500',
    ]);

    // Create a new job fair registration
    JobfairRegistrations::create($validatedData);

    return response()->json(['message' => 'Job fair registration created successfully.']);
})->name('job_fair_registrations.store');

Route::post('/offline', function (Request $request) {
    // Validate the request data
    $validatedData = $request->validate([
        'email' => 'required|email',
        'nama_lengkap' => 'required|string|max:255',
        'tanggal_acara' => 'required|date',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:Pria,Wanita',
        'nomor_hp' => 'required|string|max:15',
        'nik' => 'required|string|max:16',
        'alamat_domisili' => 'required|string|max:255',
        'kota_domisili' => 'required|string|max:100',
        'pendidikan_akhir' => 'required|string|max:100',
        'nama_sekolah' => 'nullable|string|max:255',
        'pengalaman_kerja_terakhir' => 'nullable|string|max:255',
        'pelatihan_habitat' => 'required|in:Iya,Tidak',
        'pelatihan_lain' => 'required|in:Iya,Tidak',
        'nama_lembaga_pelatihan' => 'nullable|string|max:255',
        'form_komentar' => 'nullable|string|max:500',
    ]);

    // Create a new job fair registration
    NewRegisters::create($validatedData);

    return response()->json(['message' => 'Job fair registration created successfully.']);
})->name('offline.store');
