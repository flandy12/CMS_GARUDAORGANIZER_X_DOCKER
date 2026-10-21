<?php

namespace App\Http\Controllers;

use App\Library\Enums\Status;
use App\Models\AboutUs;
use App\Models\ContactUs;
use App\Models\Home;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $modules = collect([
            ['name' => 'Beranda', 'description' => 'Banner, partner, pencapaian, dan testimoni', 'route' => 'home', 'permission' => 'view home', 'model' => Home::class, 'color' => 'indigo'],
            ['name' => 'Layanan', 'description' => 'Daftar layanan dan detail penawaran', 'route' => 'service', 'permission' => 'view service', 'model' => Service::class, 'color' => 'sky'],
            ['name' => 'Proyek', 'description' => 'Portofolio dan hasil pekerjaan', 'route' => 'project', 'permission' => 'view project', 'model' => Project::class, 'color' => 'amber'],
            ['name' => 'Tentang Kami', 'description' => 'Profil, visi, dan misi perusahaan', 'route' => 'about_us', 'permission' => 'view about us', 'model' => AboutUs::class, 'color' => 'emerald'],
            ['name' => 'Kontak', 'description' => 'Email dan nomor WhatsApp publik', 'route' => 'contact_us', 'permission' => 'view contact us', 'model' => ContactUs::class, 'color' => 'rose'],
        ])->filter(fn (array $module) => $request->user()->can($module['permission']))
            ->map(function (array $module) {
                $stats = $this->moduleStats($module['model']);
                unset($module['model'], $module['permission']);

                return array_merge($module, $stats);
            })->values();

        return Inertia::render('Dashboard', [
            'modules' => $modules,
            'summary' => [
                'accessible_modules' => $modules->count(),
                'active_content' => $modules->sum(fn (array $module) => $module['active_count'] ?? 0),
                'roles' => $request->user()->getRoleNames()->values(),
            ],
        ]);
    }

    private function moduleStats(string $modelClass): array
    {
        try {
            /** @var Model $model */
            $model = new $modelClass;
            $latest = $model->newQuery()->latest('updated_at')->first(['updated_at']);

            return [
                'active_count' => $model->newQuery()->where('status', Status::ACTIVE)->count(),
                'updated_at' => optional($latest?->updated_at)->toIso8601String(),
                'available' => true,
            ];
        } catch (Throwable) {
            return ['active_count' => null, 'updated_at' => null, 'available' => false];
        }
    }
}
