<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class UserController extends Controller
{
    protected $model = User::class;

    public function searchableBy(): array
    {
        return ['name', 'email', 'phone'];
    }

    public function filterableBy(): array
    {
        return ['status'];
    }

    public function sortableBy(): array
    {
        return ['name', 'email', 'created_at', 'status'];
    }

    public function includes(): array
    {
        return ['roles', 'roles.permissions'];
    }

    public function alwaysIncludes(): array
    {
        return ['roles'];
    }

    protected function beforeSave(Request $request, $entity): void
    {
        if (!$entity->exists && empty($entity->password)) {
            $entity->password = 'password123#';
        }
        if (empty($entity->avatar_url)) {
            $entity->avatar_url = 'https://cdn.quasar.dev/img/avatar.png';
        }
        if (empty($entity->status)) {
            $entity->status = 'active';
        }
    }

    protected function afterSave(Request $request, $entity): void
    {
        if ($request->has('role_slug')) {
            $role = Role::where('slug', $request->input('role_slug'))->first();
            if ($role) {
                $entity->roles()->sync([$role->id]);
            }
        } elseif ($request->has('role_id')) {
            $entity->roles()->sync([(int) $request->input('role_id')]);
        }
    }

    /**
     * Lista todos los roles y sus permisos asociados
     */
    public function roles(): JsonResponse
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'success' => true,
            'data' => $roles,
        ]);
    }
}

