<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\JobfairRegistrations;
use App\Models\NewRegisters;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([
	'auth:sanctum',
	'json.response',
	'cors'
])->group(function () {
	Route::get("/home", [\App\Http\Controllers\Garuda\HomeController::class, 'show'])->name("home.show");

	Route::get("/project", [\App\Http\Controllers\Garuda\ProjectController::class, 'show'])->name("project.show");

	Route::get("/service", [\App\Http\Controllers\Garuda\ServiceController::class, 'show'])->name("service.show");

	Route::get("/about-us", [\App\Http\Controllers\Garuda\AboutUsController::class, 'show'])->name("about_us.show");

	Route::get("/contact-us-information", [\App\Http\Controllers\Garuda\ContactUsController::class, 'information'])->name('contact_us_information.show');
});

Route::post("/contact-us/send", [\App\Http\Controllers\Garuda\ContactUsController::class, 'sendMessage'])->name("contact_us.send");

Route::post('/job-fair-registrations', function(Request $request) {
    // Validate the request data
    $validatedData = $request->validate([
        'email' => 'required|email|unique:job_fair_registrations,email',
        'nama_lengkap' => 'required|string|max:255',
        'tanggal_acara' => 'required|string|max:100',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:Pria,Wanita',
        'nomor_hp' => 'required|string|max:15',
        'nik' => 'required|string|max:16',
        'alamat_domisili' => 'required|string|max:255',
        'kota_domisili' => 'required|string|max:100',
        'pendidikan_akhir' => 'required|string|max:100',
        'nama_sekolah' => 'required|string|max:255',
        'pengalaman_kerja_terakhir' => 'nullable|string|max:255',
        'pelatihan_habitat' => 'required|in:Iya,Tidak',
        'pelatihan_lain' => 'required|in:Iya,Tidak',
        'nama_lembaga_pelatihan' => 'nullable|string|max:255',
        'form_komentar' => 'nullable|string|max:500',
		'konfirmasi' => 'required',
		'konfirmasi_event' => 'required'
    ]);
    
    // Create a new job fair registration
    JobfairRegistrations::create($validatedData);

    return response()->json(['message' => 'Job fair registration created successfully.']);
})->name('job_fair_registrations.store');

Route::post('/offline', function(Request $request) {
    // Validate the request data
    $validatedData = $request->validate([
        'email' => 'required|email|unique:job_fair_registrations,email',
        'nama_lengkap' => 'required|string|max:255',
        'tanggal_acara' => 'required|string|max:100',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:Pria,Wanita',
        'nomor_hp' => 'required|string|max:15',
        'nik' => 'required|string|max:16',
        'alamat_domisili' => 'required|string|max:255',
        'kota_domisili' => 'required|string|max:100',
        'pendidikan_akhir' => 'required|string|max:100',
        'nama_sekolah' => 'required|string|max:255',
        'pengalaman_kerja_terakhir' => 'nullable|string|max:255',
        'pelatihan_habitat' => 'required|in:Iya,Tidak',
        'pelatihan_lain' => 'required|in:Iya,Tidak',
        'nama_lembaga_pelatihan' => 'nullable|string|max:255',
        'form_komentar' => 'nullable|string|max:500',
		'konfirmasi' => 'required',
		'konfirmasi_event' => 'required'
    ]);
    
    // Create a new job fair registration
    NewRegisters::create($validatedData);

    return response()->json(['message' => 'Job fair registration created successfully.']);
})->name('job_fair_registrations.store');