<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Contenidos (Páginas, Noticias, Carreras, Eventos)
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('type', 30)->default('page'); // page, post, course, event, service
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content_html')->nullable(); // Pre-render SSR / Fallback SEO
            $table->string('featured_image', 500)->nullable();
            $table->string('status', 20)->default('draft'); // draft, published, scheduled, archived
            $table->string('template', 50)->default('full-width'); // landing, full-width, with-sidebar
            $table->string('comment_status', 20)->default('closed'); // open, closed
            $table->integer('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 2. Pivote content_term
        Schema::create('content_term', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
            $table->foreignId('term_id')->constrained('terms')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['content_id', 'term_id']);
        });

        // 3. Catálogo de Componentes (component_types)
        Schema::create('component_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique(); // hero_slider, data_table, feature_cards, etc.
            $table->string('category', 50)->default('content'); // hero, content, academic, interactive, social
            $table->string('icon', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->json('schema')->nullable(); // JSON Schema para formulario autogenerado
            $table->json('default_data')->nullable(); // Payload inicial
            $table->json('default_settings')->nullable(); // Estilos y padding iniciales
            $table->boolean('is_system')->default(true); // true = SFC Vue, false = Dinámico
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Secciones del Page Builder (content_sections)
        Schema::create('content_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
            $table->foreignId('component_type_id')->nullable()->constrained('component_types')->onDelete('set null');
            $table->string('component_name', 100);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Padding, colores, fondos, clases
            $table->json('data')->nullable(); // Payload de datos del componente
            $table->timestamps();
        });

        // 5. Metadatos de Contenido (content_metas)
        Schema::create('content_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
            $table->string('meta_key', 100);
            $table->longText('meta_value')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            $table->index(['content_id', 'meta_key']);
        });

        // 6. Revisiones e Historial (content_revisions)
        Schema::create('content_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->json('sections_snapshot')->nullable();
            $table->longText('content_html')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_revisions');
        Schema::dropIfExists('content_metas');
        Schema::dropIfExists('content_sections');
        Schema::dropIfExists('component_types');
        Schema::dropIfExists('content_term');
        Schema::dropIfExists('contents');
    }
};
