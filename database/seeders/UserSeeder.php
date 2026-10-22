<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
            'name' => config('cms.seed_users.admin.name'),
                'email' => config('cms.seed_users.admin.email'),
                'password' => config('cms.seed_users.admin.password'),
                'role' => 'admin',
            ],
            [
                'name' => config('cms.seed_users.editor.name'),
                'email' => config('cms.seed_users.editor.email'),
                'password' => config('cms.seed_users.editor.password'),
                'role' => 'editor',
            ],
            [
                'name' => config('cms.seed_users.viewer.name'),
                'email' => config('cms.seed_users.viewer.email'),
                'password' => config('cms.seed_users.viewer.password'),
                'role' => 'viewer',
            ],
        ];

        foreach ($users as $seedUser) {
            $user = User::updateOrCreate(
                ['email' => $seedUser['email']],
                [
                    'name' => $seedUser['name'],
                    'password' => Hash::make($seedUser['password']),
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$seedUser['role']]);
        }
    }
}
