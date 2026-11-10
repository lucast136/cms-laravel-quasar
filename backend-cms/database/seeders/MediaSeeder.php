<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::first()?->id ?? 1;

        $mediaItems = [
            [
                'title' => 'Banner Principal Slide 1',
                'filename' => 'slide1.jpg',
                'filepath' => '/img/banner/slide1.jpg',
                'disk' => 'public',
                'mime_type' => 'image/jpeg',
                'file_size' => 348160,
                'dimensions' => ['width' => 1920, 'height' => 800, 'label' => '1920x800'],
                'alt_text' => 'Estudiantes del IESTP DEPERU MASTER en aula tecnológica',
            ],
            [
                'title' => 'Banner Principal Slide 2',
                'filename' => 'slide2.jpg',
                'filepath' => '/img/banner/slide2.jpg',
                'disk' => 'public',
                'mime_type' => 'image/jpeg',
                'file_size' => 419840,
                'dimensions' => ['width' => 1920, 'height' => 800, 'label' => '1920x800'],
                'alt_text' => 'Docente guiando a postulantes',
            ],
            [
                'title' => 'Imagen Institucional Nosotros',
                'filename' => 'about-01.jpg',
                'filepath' => '/img/content/about-01.jpg',
                'disk' => 'public',
                'mime_type' => 'image/jpeg',
                'file_size' => 225280,
                'dimensions' => ['width' => 800, 'height' => 600, 'label' => '800x600'],
                'alt_text' => 'Campus institucional en Lircay',
            ],
            [
                'title' => 'Video Institucional Portada',
                'filename' => 'why-choose-img.jpg',
                'filepath' => '/img/content/why-choose-img.jpg',
                'disk' => 'public',
                'mime_type' => 'image/jpeg',
                'file_size' => 286720,
                'dimensions' => ['width' => 1000, 'height' => 650, 'label' => '1000x650'],
                'alt_text' => 'Laboratorio de redes y sistemas',
            ],
            [
                'title' => 'Logo Principal Institucional',
                'filename' => 'logo-inner.png',
                'filepath' => '/img/logos/logo-inner.png',
                'disk' => 'public',
                'mime_type' => 'image/png',
                'file_size' => 66560,
                'dimensions' => ['width' => 350, 'height' => 90, 'label' => '350x90'],
                'alt_text' => 'Logotipo oficial DEPERU MASTER',
            ],
            [
                'title' => 'Prospecto de Admisión 2026-I',
                'filename' => 'prospecto_admision_2026.pdf',
                'filepath' => '/docs/prospecto_admision_2026.pdf',
                'disk' => 'public',
                'mime_type' => 'application/pdf',
                'file_size' => 2516582,
                'dimensions' => ['label' => 'PDF'],
                'alt_text' => 'Bases y reglamento de admisión',
            ],
        ];

        foreach ($mediaItems as $item) {
            Media::updateOrCreate(
                ['filename' => $item['filename']],
                array_merge($item, ['user_id' => $userId])
            );
        }
    }
}

