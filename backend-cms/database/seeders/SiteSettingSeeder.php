<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $groupedSettings = [
            'identity' => [
                'site_name' => 'IESTP DEPERU MASTER',
                'site_slogan' => 'Donde tu talento se convierte en profesión',
                'site_logo' => '/img/logos/logo-inner.png',
                'site_favicon' => '/favicon.ico',
            ],
            'contact' => [
                'phone' => '(01) 456-7890',
                'whatsapp' => '+51 987 654 321',
                'email' => 'informes@deperumaster.edu.pe',
                'address' => 'Av. Universitaria s/n, Lircay, Angaraes - Huancavelica',
                'hours' => 'Lunes a Viernes de 08:00 a 18:00 hrs',
            ],
            'social' => [
                'facebook' => 'facebook.com/deperumaster',
                'instagram' => 'instagram.com/deperumaster',
                'youtube' => 'youtube.com/@deperumaster',
                'tiktok' => 'tiktok.com/@deperumaster',
            ],
            'appearance' => [
                'primary' => '#003278',
                'secondary' => '#ED3237',
                'accent' => '#004B87',
                'warning' => '#E5A93C',
            ],
            'seo' => [
                'meta_title' => 'IESTP DEPERU MASTER | Carreras Técnicas Profesionales Licenciadas',
                'meta_description' => 'Instituto de Educación Superior Tecnológico Privado DEPERU MASTER. Formando profesionales técnicos líderes en tecnología, gestión y comunicaciones en Huancavelica.',
                'google_analytics_id' => 'G-DPM2026CMS',
            ],
        ];

        foreach ($groupedSettings as $group => $items) {
            foreach ($items as $key => $value) {
                SiteSetting::updateOrCreate(
                    ['group_name' => $group, 'setting_key' => $key],
                    ['setting_value' => $value, 'is_public' => true]
                );
            }
        }
    }
}

