<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all()->keyBy('slug');

        $users = [
            [
                'name' => 'Super Administrador',
                'email' => 'admin@deperumaster.edu.pe',
                'password' => Hash::make('password123#'),
                'avatar_url' => 'https://cdn.quasar.dev/img/avatar.png',
                'phone' => '(01) 456-7890',
                'status' => 'active',
                'role_slug' => 'super_admin',
            ],
            [
                'name' => 'Coordinador Académico (Editor)',
                'email' => 'editor@deperumaster.edu.pe',
                'password' => Hash::make('password123#'),
                'avatar_url' => 'https://cdn.quasar.dev/img/avatar2.jpg',
                'phone' => '+51 987 654 321',
                'status' => 'active',
                'role_slug' => 'editor',
            ],
            [
                'name' => 'Prensa e Imagen Institucional',
                'email' => 'prensa@deperumaster.edu.pe',
                'password' => Hash::make('password123#'),
                'avatar_url' => 'https://cdn.quasar.dev/img/avatar4.jpg',
                'phone' => '+51 954 321 987',
                'status' => 'active',
                'role_slug' => 'redactor',
            ],
        ];

        foreach ($users as $u) {
            $roleSlug = $u['role_slug'];
            unset($u['role_slug']);

            $user = User::updateOrCreate(
                ['email' => $u['email']],
                array_merge($u, ['email_verified_at' => now()])
            );

            if ($roles->has($roleSlug)) {
                $user->roles()->sync([$roles->get($roleSlug)->id]);
            }
        }
    }
}
