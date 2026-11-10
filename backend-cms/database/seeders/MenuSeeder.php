<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $pagesBySlug = Content::where('type', 'page')->get()->keyBy('slug');

        $menus = [
            [
                'name' => 'Menú Principal (Cabecera)',
                'location' => 'header_main',
                'is_active' => true,
                'items' => [
                    ['title' => 'Inicio', 'url' => '/', 'slug' => 'inicio', 'icon_class' => 'home', 'sort_order' => 1],
                    ['title' => 'Nosotros', 'url' => '/nosotros', 'slug' => 'nosotros', 'icon_class' => 'info', 'sort_order' => 2],
                    ['title' => 'Carreras', 'url' => '/carreras', 'slug' => 'carreras', 'icon_class' => 'school', 'sort_order' => 3],
                    ['title' => 'Admisión', 'url' => '/admision', 'slug' => 'admision', 'icon_class' => 'assignment_ind', 'sort_order' => 4],
                    ['title' => 'Docentes', 'url' => '/docentes', 'slug' => 'docentes', 'icon_class' => 'groups', 'sort_order' => 5],
                    ['title' => 'Eventos', 'url' => '/eventos', 'slug' => 'eventos', 'icon_class' => 'event', 'sort_order' => 6],
                    ['title' => 'Noticias', 'url' => '/noticias', 'slug' => 'noticias', 'icon_class' => 'article', 'sort_order' => 7],
                    ['title' => 'Contacto', 'url' => '/contacto', 'slug' => 'contacto', 'icon_class' => 'contact_mail', 'sort_order' => 8],
                ],
            ],
            [
                'name' => 'Enlaces Rápidos (Footer)',
                'location' => 'footer_quick_links',
                'is_active' => true,
                'items' => [
                    ['title' => 'Identidad Institucional', 'url' => '/nosotros', 'slug' => 'nosotros', 'icon_class' => 'chevron_right', 'sort_order' => 1],
                    ['title' => 'Proceso de Admisión 2026', 'url' => '/admision', 'slug' => 'admision', 'icon_class' => 'chevron_right', 'sort_order' => 2],
                    ['title' => 'Plana Docente', 'url' => '/docentes', 'slug' => 'docentes', 'icon_class' => 'chevron_right', 'sort_order' => 3],
                    ['title' => 'Noticias y Comunicados', 'url' => '/noticias', 'slug' => 'noticias', 'icon_class' => 'chevron_right', 'sort_order' => 4],
                    ['title' => 'Mesa de Partes & Contacto', 'url' => '/contacto', 'slug' => 'contacto', 'icon_class' => 'chevron_right', 'sort_order' => 5],
                ],
            ],
            [
                'name' => 'Carreras Destacadas (Footer)',
                'location' => 'footer_courses',
                'is_active' => true,
                'items' => [
                    ['title' => 'Desarrollo de Software y TI', 'url' => '/carreras', 'slug' => 'carreras', 'icon_class' => 'computer', 'sort_order' => 1],
                    ['title' => 'Administración de Redes', 'url' => '/carreras', 'slug' => 'carreras', 'icon_class' => 'network_check', 'sort_order' => 2],
                    ['title' => 'Diseño y Animación Digital', 'url' => '/carreras', 'slug' => 'carreras', 'icon_class' => 'palette', 'sort_order' => 3],
                    ['title' => 'Contabilidad y Finanzas', 'url' => '/carreras', 'slug' => 'carreras', 'icon_class' => 'account_balance', 'sort_order' => 4],
                ],
            ],
        ];

        foreach ($menus as $menuData) {
            $menu = Menu::updateOrCreate(
                ['location' => $menuData['location']],
                [
                    'name' => $menuData['name'],
                    'is_active' => $menuData['is_active'],
                ]
            );

            // Sincronizar items
            $menu->items()->delete();
            foreach ($menuData['items'] as $item) {
                $linkedPage = $item['slug'] ? $pagesBySlug->get($item['slug']) : null;
                MenuItem::create([
                    'menu_id' => $menu->id,
                    'parent_id' => null,
                    'content_id' => $linkedPage?->id,
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'target' => '_self',
                    'icon_class' => $item['icon_class'],
                    'sort_order' => $item['sort_order'],
                ]);
            }
        }
    }
}
