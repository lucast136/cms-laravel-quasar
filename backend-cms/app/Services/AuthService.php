<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(string $email, string $password, string $deviceName = 'QuasarAdminSPA'): array
    {
        $user = User::with(['roles.permissions'])->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Tu cuenta se encuentra inactiva o suspendida.'],
            ]);
        }

        // Emisión de token Bearer con Sanctum
        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => $user->avatar_url,
                'phone' => $user->phone,
                'roles' => $user->roles->map(fn($r) => ['id' => $r->id, 'name' => $r->name, 'slug' => $r->slug]),
                'permissions' => $user->hasRole('super_admin') ? ['*'] : $user->roles->flatMap->permissions->pluck('slug')->unique()->values(),
            ],
        ];
    }

    public function logout(?User $user): bool
    {
        if ($user) {
            $user->currentAccessToken()?->delete();
        }
        return true;
    }
}
