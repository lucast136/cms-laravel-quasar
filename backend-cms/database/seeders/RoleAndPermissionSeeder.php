<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            ['name' => 'Super Administrador', 'description' => 'Acceso total y control de todo el sistema CMS']
        );

        $editor = Role::firstOrCreate(
            ['slug' => 'editor'],
            ['name' => 'Editor de Contenidos', 'description' => 'Puede editar y publicar páginas y noticias']
        );

        $redactor = Role::firstOrCreate(
            ['slug' => 'redactor'],
            ['name' => 'Redactor Académico', 'description' => 'Puede crear y editar borradores de noticias']
        );

        $permissions = [
            // Páginas & Builder
            ['slug' => 'pages.view', 'name' => 'Ver Páginas', 'module' => 'pages'],
            ['slug' => 'pages.create', 'name' => 'Crear Páginas', 'module' => 'pages'],
            ['slug' => 'pages.edit', 'name' => 'Editar Páginas', 'module' => 'pages'],
            ['slug' => 'pages.delete', 'name' => 'Eliminar Páginas', 'module' => 'pages'],
            ['slug' => 'pages.builder', 'name' => 'Usar Page Builder', 'module' => 'pages'],

            // Noticias & Blog
            ['slug' => 'posts.view', 'name' => 'Ver Noticias', 'module' => 'posts'],
            ['slug' => 'posts.create', 'name' => 'Crear Noticias', 'module' => 'posts'],
            ['slug' => 'posts.edit', 'name' => 'Editar Noticias', 'module' => 'posts'],
            ['slug' => 'posts.delete', 'name' => 'Eliminar Noticias', 'module' => 'posts'],

            // Medios
            ['slug' => 'media.view', 'name' => 'Ver Medios', 'module' => 'media'],
            ['slug' => 'media.upload', 'name' => 'Subir Medios', 'module' => 'media'],
            ['slug' => 'media.delete', 'name' => 'Eliminar Medios', 'module' => 'media'],

            // Ajustes y Menús
            ['slug' => 'menus.manage', 'name' => 'Gestionar Menús', 'module' => 'menus'],
            ['slug' => 'settings.manage', 'name' => 'Gestionar Ajustes', 'module' => 'settings'],
        ];

        $permissionIds = [];
        foreach ($permissions as $p) {
            $created = Permission::firstOrCreate(['slug' => $p['slug']], $p);
            $permissionIds[] = $created->id;
        }

        // Super Admin tiene todos los permisos
        $superAdmin->permissions()->sync($permissionIds);

        // Editor tiene permisos de páginas, blog y medios
        $editorPermissions = Permission::whereIn('module', ['pages', 'posts', 'media'])->pluck('id');
        $editor->permissions()->sync($editorPermissions);

        // Redactor tiene solo blog y ver medios
        $redactorPermissions = Permission::whereIn('slug', ['posts.view', 'posts.create', 'posts.edit', 'media.view'])->pluck('id');
        $redactor->permissions()->sync($redactorPermissions);
    }
}
