<?php

namespace Database\Seeders;

use App\Models\ComponentType;
use App\Models\Content;
use App\Models\ContentMeta;
use App\Models\ContentSection;
use App\Models\User;
use Illuminate\Database\Seeder;

class PageAndSectionSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::first()?->id ?? 1;
        $componentTypes = ComponentType::all()->keyBy('code');

        $pages = [
            // 1. Portada Principal
            [
                'title' => 'Página Principal (Inicio)',
                'slug' => 'inicio',
                'template' => 'landing',
                'status' => 'published',
                'featured_image' => '/img/banner/slide1.jpg',
                'excerpt' => 'Sitio web oficial del IESTP DEPERU MASTER. Formación superior tecnológica licenciada en carreras profesionales de alta demanda.',
                'sort_order' => 1,
                'meta_title' => 'IESTP DEPERU MASTER - Educación Técnica Superior Licenciada',
                'meta_keywords' => 'instituto de educacion superior tecnologico, carreras tecnicas, admision 2026, lircay huancavelica, educacion licenciada',
                'sections' => [
                    ['code' => 'hero_slider', 'name' => 'HeroSlider', 'order' => 1],
                    ['code' => 'feature_cards', 'name' => 'FeatureCards', 'order' => 2],
                    ['code' => 'about_section', 'name' => 'AboutSection', 'order' => 3],
                    ['code' => 'courses_section', 'name' => 'CoursesSection', 'order' => 4],
                    ['code' => 'categories_grid', 'name' => 'CategoriesGrid', 'order' => 5],
                    ['code' => 'instructors_grid', 'name' => 'InstructorsGrid', 'order' => 6],
                    ['code' => 'why_choose_us', 'name' => 'WhyChooseUs', 'order' => 7],
                    ['code' => 'stats_counter', 'name' => 'StatsCounter', 'order' => 8],
                    ['code' => 'testimonials_slider', 'name' => 'TestimonialsSlider', 'order' => 9],
                    ['code' => 'learning_process', 'name' => 'LearningProcess', 'order' => 10],
                    ['code' => 'upcoming_events', 'name' => 'UpcomingEvents', 'order' => 11],
                    ['code' => 'latest_blog', 'name' => 'LatestBlog', 'order' => 12],
                    ['code' => 'partners_slider', 'name' => 'PartnersSlider', 'order' => 13],
                ],
            ],

            // 2. Nosotros / Identidad Institucional
            [
                'title' => 'Sobre Nosotros - Identidad Institucional',
                'slug' => 'nosotros',
                'template' => 'full-width',
                'status' => 'published',
                'featured_image' => '/img/content/about-01.jpg',
                'excerpt' => 'Misión, visión, plana directiva, historia y valores institucionales del IESTP DEPERU MASTER.',
                'sort_order' => 2,
                'meta_title' => 'Sobre Nosotros | IESTP DEPERU MASTER',
                'meta_keywords' => 'nosotros deperu master, mision y vision institucional, historia instituto superior, plana directiva',
                'sections' => [
                    ['code' => 'about_section', 'name' => 'AboutSection', 'order' => 1],
                    ['code' => 'why_choose_us', 'name' => 'WhyChooseUs', 'order' => 2],
                    ['code' => 'stats_counter', 'name' => 'StatsCounter', 'order' => 3],
                    ['code' => 'testimonials_slider', 'name' => 'TestimonialsSlider', 'order' => 4],
                ],
            ],

            // 3. Carreras Profesionales
            [
                'title' => 'Carreras Profesionales & Oferta Académica',
                'slug' => 'carreras',
                'template' => 'with-sidebar',
                'status' => 'published',
                'featured_image' => '/img/courses/course-01.jpg',
                'excerpt' => 'Catálogo oficial de carreras técnicas de 3 años con titulación a nombre de la nación y certificación modular progresiva.',
                'sort_order' => 3,
                'meta_title' => 'Carreras Profesionales Técnicas | IESTP DEPERU MASTER',
                'meta_keywords' => 'carreras tecnicas, ingenieria de software, redes y comunicaciones, contabilidad, diseño multimedia, certificacion modular',
                'sections' => [
                    ['code' => 'categories_grid', 'name' => 'CategoriesGrid', 'order' => 1],
                    ['code' => 'courses_section', 'name' => 'CoursesSection', 'order' => 2],
                    ['code' => 'learning_process', 'name' => 'LearningProcess', 'order' => 3],
                ],
            ],

            // 4. Plana Docente
            [
                'title' => 'Plana Docente & Coordinadores Académicos',
                'slug' => 'docentes',
                'template' => 'full-width',
                'status' => 'published',
                'featured_image' => '/img/team/team-01.jpg',
                'excerpt' => 'Conoce a nuestros docentes e instructores especializados con amplia trayectoria laboral y vocación pedagógica.',
                'sort_order' => 4,
                'meta_title' => 'Plana Docente Destacada | IESTP DEPERU MASTER',
                'meta_keywords' => 'docentes instituto deperu master, profesores especialistas ti, plana academica calificada',
                'sections' => [
                    ['code' => 'instructors_grid', 'name' => 'InstructorsGrid', 'order' => 1],
                    ['code' => 'why_choose_us', 'name' => 'WhyChooseUs', 'order' => 2],
                ],
            ],

            // 5. Eventos Académicos
            [
                'title' => 'Agenda de Eventos, Talleres y Webinars',
                'slug' => 'eventos',
                'template' => 'full-width',
                'status' => 'published',
                'featured_image' => '/img/content/event-01.jpg',
                'excerpt' => 'Cronograma de seminarios web, talleres prácticos gratuitos, charlas vocacionales y ceremonias institucionales.',
                'sort_order' => 5,
                'meta_title' => 'Agenda de Eventos y Webinars | IESTP DEPERU MASTER',
                'meta_keywords' => 'eventos academicos, webinars de tecnologia, talleres gratuitos, capacitacion superior',
                'sections' => [
                    ['code' => 'upcoming_events', 'name' => 'UpcomingEvents', 'order' => 1],
                ],
            ],

            // 6. Blog y Noticias
            [
                'title' => 'Noticias, Artículos & Comunicados Oficiales',
                'slug' => 'noticias',
                'template' => 'with-sidebar',
                'status' => 'published',
                'featured_image' => '/img/blog/blog-01.jpg',
                'excerpt' => 'Entérate de las últimas novedades académicas, firmas de convenios y comunicados oficiales del IESTP DEPERU MASTER.',
                'sort_order' => 6,
                'meta_title' => 'Noticias y Comunicados Oficiales | IESTP DEPERU MASTER',
                'meta_keywords' => 'noticias deperu master, comunicados institucionales, blog academico, articulos de tecnologia',
                'sections' => [
                    ['code' => 'latest_blog', 'name' => 'LatestBlog', 'order' => 1],
                ],
            ],

            // 7. Proceso de Admisión
            [
                'title' => 'Admisión 2026 & Cronograma Oficial',
                'slug' => 'admision',
                'template' => 'full-width',
                'status' => 'published',
                'featured_image' => '/img/banner/slide2.jpg',
                'excerpt' => 'Fechas de postulación ordinaria, examen de admisión, requisitos, costos de matrícula y pasos de inscripción.',
                'sort_order' => 7,
                'meta_title' => 'Admisión 2026-I | IESTP DEPERU MASTER',
                'meta_keywords' => 'admision 2026, cronograma examen admision, inscripcion ordinaria, vacantes carreras tecnicas',
                'sections' => [
                    ['code' => 'data_table', 'name' => 'DataTableBlock', 'order' => 1],
                    ['code' => 'learning_process', 'name' => 'LearningProcess', 'order' => 2],
                    ['code' => 'why_choose_us', 'name' => 'WhyChooseUs', 'order' => 3],
                ],
            ],

            // 8. Contacto y Mesa de Partes
            [
                'title' => 'Contacto, Sede Institucional & Mesa de Partes',
                'slug' => 'contacto',
                'template' => 'full-width',
                'status' => 'published',
                'featured_image' => '/img/content/contact-img-01.jpg',
                'excerpt' => 'Atención al postulante, ubicación del campus en Lircay, teléfonos de admisión, correo institucional y formulario de contacto.',
                'sort_order' => 8,
                'meta_title' => 'Contacto y Sede Institucional | IESTP DEPERU MASTER',
                'meta_keywords' => 'contacto deperu master, telefono admision, campus lircay, mesa de partes virtual',
                'sections' => [
                    ['code' => 'contact_section', 'name' => 'ContactSection', 'order' => 1],
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            $page = Content::updateOrCreate(
                ['slug' => $pageData['slug'], 'type' => 'page'],
                [
                    'author_id' => $authorId,
                    'title' => $pageData['title'],
                    'template' => $pageData['template'],
                    'status' => $pageData['status'],
                    'featured_image' => $pageData['featured_image'],
                    'excerpt' => $pageData['excerpt'],
                    'sort_order' => $pageData['sort_order'],
                    'published_at' => now(),
                ]
            );

            // Metadatos para SEO 100% en español
            ContentMeta::updateOrCreate(
                ['content_id' => $page->id, 'meta_key' => 'meta_title'],
                ['meta_value' => $pageData['meta_title']]
            );
            ContentMeta::updateOrCreate(
                ['content_id' => $page->id, 'meta_key' => 'meta_description'],
                ['meta_value' => $pageData['excerpt']]
            );
            ContentMeta::updateOrCreate(
                ['content_id' => $page->id, 'meta_key' => 'meta_keywords'],
                ['meta_value' => $pageData['meta_keywords']]
            );
            ContentMeta::updateOrCreate(
                ['content_id' => $page->id, 'meta_key' => 'og_title'],
                ['meta_value' => $pageData['meta_title']]
            );

            foreach ($pageData['sections'] as $sec) {
                $compType = $componentTypes->get($sec['code']);
                if ($compType) {
                    $existing = ContentSection::where('content_id', $page->id)
                        ->where('sort_order', $sec['order'])
                        ->first();

                    if ($existing) {
                        $isSameType = $existing->component_type_id === $compType->id;
                        $mergedData = $isSameType
                            ? array_merge($compType->default_data ?? [], $existing->data ?? [])
                            : ($compType->default_data ?? []);
                        if ($isSameType) {
                            foreach ($compType->default_data ?? [] as $k => $v) {
                                if (is_array($v) && empty($existing->data[$k])) {
                                    $mergedData[$k] = $v;
                                }
                            }
                        }
                        $existing->update([
                            'component_type_id' => $compType->id,
                            'component_name' => $sec['name'],
                            'settings' => $isSameType
                                ? array_merge($compType->default_settings ?? [], $existing->settings ?? [])
                                : ($compType->default_settings ?? []),
                            'data' => $mergedData,
                        ]);
                    } else {
                        ContentSection::create([
                            'content_id' => $page->id,
                            'component_type_id' => $compType->id,
                            'component_name' => $sec['name'],
                            'sort_order' => $sec['order'],
                            'is_active' => true,
                            'settings' => $compType->default_settings ?? [],
                            'data' => $compType->default_data ?? [],
                        ]);
                    }
                }
            }
        }
    }
}
