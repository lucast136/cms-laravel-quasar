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
        // 1. Configuraciones Globales
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group_name', 50)->default('general'); // identity, contact, social, appearance, seo
            $table->string('setting_key', 100)->unique();
            $table->json('setting_value')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        // 2. Logs de Auditoría
        Schema::create('site_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action', 100);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_logs');
        Schema::dropIfExists('site_settings');
    }
};
