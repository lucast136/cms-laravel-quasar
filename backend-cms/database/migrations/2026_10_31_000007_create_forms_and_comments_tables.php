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
        // 1. Comentarios
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->string('author_avatar')->nullable();
            $table->text('content');
            $table->string('status', 20)->default('pending'); // pending, approved, spam, trash
            $table->timestamps();
        });

        // 2. Tipos de Formularios
        Schema::create('form_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique(); // contact, admission_lead, newsletter
            $table->string('description', 255)->nullable();
            $table->json('schema')->nullable();
            $table->timestamps();
        });

        // 3. Envíos de Formularios
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('form_type', 50);
            $table->json('payload');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status', 20)->default('new'); // new, contacted, enrolled, discarded
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_types');
        Schema::dropIfExists('comments');
    }
};
