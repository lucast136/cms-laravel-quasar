<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\ContentMeta;
use App\Models\Taxonomy;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::first()?->id ?? 1;

        $categoryTax = Taxonomy::updateOrCreate(
            ['slug' => 'category'],
            [
                'name' => 'Categorías del Blog',
                'description' => 'Categorías principales para artículos, comunicados y noticias institucionales.',
            ]
        );

        $tagTax = Taxonomy::updateOrCreate(
            ['slug' => 'post_tag'],
            [
                'name' => 'Etiquetas',
                'description' => 'Palabras clave de clasificación temática.',
            ]
        );

        $termsData = [
            ['name' => 'Admisión', 'slug' => 'admision', 'badge_color' => 'primary', 'sort_order' => 1],
            ['name' => 'Tecnología', 'slug' => 'tecnologia', 'badge_color' => 'teal', 'sort_order' => 2],
            ['name' => 'Convenios', 'slug' => 'convenios', 'badge_color' => 'indigo', 'sort_order' => 3],
            ['name' => 'Vida Estudiantil', 'slug' => 'vida-estudiantil', 'badge_color' => 'orange', 'sort_order' => 4],
            ['name' => 'Institucional', 'slug' => 'institucional', 'badge_color' => 'blue-grey', 'sort_order' => 5],
        ];

        $createdTerms = [];
        foreach ($termsData as $t) {
            $createdTerms[$t['slug']] = Term::updateOrCreate(
                ['taxonomy_id' => $categoryTax->id, 'slug' => $t['slug']],
                [
                    'name' => $t['name'],
                    'badge_color' => $t['badge_color'],
                    'sort_order' => $t['sort_order'],
                ]
            );
        }

        $tagsData = [
            ['name' => 'MINEDU', 'slug' => 'minedu', 'sort_order' => 1],
            ['name' => 'Admisión 2026', 'slug' => 'admision-2026', 'sort_order' => 2],
            ['name' => 'Empleabilidad', 'slug' => 'empleabilidad', 'sort_order' => 3],
            ['name' => 'Becas', 'slug' => 'becas', 'sort_order' => 4],
            ['name' => 'Innovación TI', 'slug' => 'innovacion-ti', 'sort_order' => 5],
        ];

        $createdTags = [];
        foreach ($tagsData as $tg) {
            $createdTags[$tg['slug']] = Term::updateOrCreate(
                ['taxonomy_id' => $tagTax->id, 'slug' => $tg['slug']],
                [
                    'name' => $tg['name'],
                    'badge_color' => 'primary',
                    'sort_order' => $tg['sort_order'],
                ]
            );
        }

        $posts = [
            [
                'title' => 'Inicio del Proceso de Admisión 2026-I en el IESTP DEPERU MASTER',
                'slug' => 'inicio-proceso-admision-2026-i',
                'template' => 'with-sidebar',
                'excerpt' => 'Conoce las fechas de inscripción, requisitos y beneficios para postular a nuestras carreras profesionales técnicas.',
                'content_html' => '<p>El <strong>IESTP DEPERU MASTER</strong> anuncia oficialmente la apertura del Proceso de Admisión 2026-I dirigido a jóvenes y adultos de toda la región que buscan formarse en carreras técnicas de alta empleabilidad y rápida inserción laboral.</p><p>Nuestro modelo educativo combina formación práctica en laboratorios especializados con acompañamiento docente personalizado desde el primer ciclo académico.</p><blockquote><p>Nuestra misión es transformar el talento regional en profesionales técnicos líderes, éticos y altamente competitivos para la industria tecnológica y empresarial del Perú.</p><cite>Dirección General : IESTP DEPERU MASTER</cite></blockquote><h4>Beneficios exclusivos para nuestros ingresantes</h4><p>Al formar parte de nuestra comunidad estudiantil accederás a herramientas modernas y certificaciones progresivas:</p><ul class="blog-detail-list"><li>Certificación modular oficial al culminar cada año académico.</li><li>Laboratorios de cómputo, redes y multimedia de última generación.</li><li>Convenios vigentes para prácticas preprofesionales remuneradas.</li><li>Acceso a biblioteca virtual y plataforma educativa las 24 horas.</li><li>Programa de becas por excelencia académica y mérito deportivo.</li></ul>',
                'featured_image' => '/img/blog/blog-01.jpg',
                'status' => 'published',
                'term_slug' => 'admision',
                'tag_slugs' => ['admision-2026', 'minedu', 'becas'],
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Nuevos Convenios Empresariales para Prácticas Preprofesionales',
                'slug' => 'nuevos-convenios-empresariales-practicas',
                'template' => 'with-sidebar',
                'excerpt' => 'Firmamos alianzas estratégicas con empresas líderes de la región para garantizar la inserción laboral de nuestros estudiantes.',
                'content_html' => '<p>Comprometidos con el éxito profesional de nuestra comunidad estudiantil, el <strong>IESTP DEPERU MASTER</strong> ha suscrito nuevos convenios interinstitucionales con empresas del sector tecnológico, financiero y de telecomunicaciones.</p><blockquote><p>La vinculación directa entre el instituto y el sector productivo garantiza que nuestros egresados respondan a las necesidades reales del mercado actual.</p><cite>Coordinación de Empleabilidad y Seguimiento al Egresado</cite></blockquote><h4>Ventajas de la Bolsa de Trabajo Institucional</h4><ul class="blog-detail-list"><li>Convalidación inmediata de experiencias formativas en situaciones reales de trabajo.</li><li>Mentoría directa por especialistas en activo de empresas aliadas.</li><li>Altas tasas de contratación al finalizar el sexto semestre académico.</li></ul>',
                'featured_image' => '/img/blog/blog-02.jpg',
                'status' => 'published',
                'term_slug' => 'convenios',
                'tag_slugs' => ['empleabilidad', 'innovacion-ti'],
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Inauguración de Laboratorios de Desarrollo de Software y Redes',
                'slug' => 'inauguracion-laboratorios-software-redes',
                'template' => 'with-sidebar',
                'excerpt' => 'Equipamiento de vanguardia para potenciar el aprendizaje práctico en Arquitectura de Plataformas y Servicios de TI.',
                'content_html' => '<p>Nuestros estudiantes cuentan ahora con estaciones de trabajo de alto rendimiento y servidores dedicados para prácticas de virtualización, ciberseguridad, diseño multimedia y desarrollo web full-stack.</p><blockquote><p>Aprender haciendo es el pilar fundamental de nuestra propuesta pedagógica tecnológica.</p><cite>Jefatura de Unidad Académica</cite></blockquote><p>Los nuevos ambientes están abiertos para todos los ciclos de las carreras de Desarrollo de Sistemas, Redes y Diseño Digital.</p>',
                'featured_image' => '/img/blog/blog-03.jpg',
                'status' => 'published',
                'term_slug' => 'tecnologia',
                'tag_slugs' => ['innovacion-ti', 'minedu'],
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'Comunicado Oficial: Reglamento de Becas por Excelencia Académica 2026',
                'slug' => 'guia-becas-excelencia-academica-2026',
                'template' => 'full-width',
                'excerpt' => 'Conoce los requisitos y el cronograma oficial para postular al programa de becas integrales y medias becas por rendimiento destacado.',
                'content_html' => '<p>La Dirección General del <strong>IESTP DEPERU MASTER</strong> pone en conocimiento de toda la comunidad estudiantil el cronograma oficial de postulación a becas de estímulo académico para el semestre 2026-I.</p><blockquote><p>Premiamos la constancia, disciplina y excelencia de nuestros mejores estudiantes de cada programa de estudios.</p><cite>Secretaría Académica : Comunicado Oficial N° 014-2026</cite></blockquote><h4>Requisitos para la postulación</h4><ul class="blog-detail-list"><li>Haber ocupado el primer o segundo puesto en el semestre inmediato anterior.</li><li>No registrar deudas pendientes ni sanciones disciplinarias.</li><li>Presentar solicitud dirigida a Bienestar Estudiantil en Mesa de Partes.</li></ul>',
                'featured_image' => '/img/blog/blog-01.jpg',
                'status' => 'published',
                'term_slug' => 'institucional',
                'tag_slugs' => ['becas', 'admision-2026'],
                'published_at' => now()->subDays(10),
            ],
        ];

        foreach ($posts as $idx => $postData) {
            $termSlug = $postData['term_slug'];
            $tagSlugs = $postData['tag_slugs'] ?? [];
            unset($postData['term_slug'], $postData['tag_slugs']);

            $post = Content::updateOrCreate(
                ['slug' => $postData['slug'], 'type' => 'post'],
                array_merge($postData, [
                    'author_id' => $authorId,
                    'comment_status' => 'open',
                    'sort_order' => $idx + 1,
                ])
            );

            // Metadatos SEO en español
            ContentMeta::updateOrCreate(
                ['content_id' => $post->id, 'meta_key' => 'meta_title'],
                ['meta_value' => $post->title . ' | IESTP DEPERU MASTER']
            );
            ContentMeta::updateOrCreate(
                ['content_id' => $post->id, 'meta_key' => 'meta_description'],
                ['meta_value' => $post->excerpt]
            );

            $syncIds = [];
            if (isset($createdTerms[$termSlug])) {
                $syncIds[] = $createdTerms[$termSlug]->id;
            }
            foreach ($tagSlugs as $ts) {
                if (isset($createdTags[$ts])) {
                    $syncIds[] = $createdTags[$ts]->id;
                }
            }
            if (!empty($syncIds)) {
                $post->terms()->sync($syncIds);
            }
        }
    }
}
