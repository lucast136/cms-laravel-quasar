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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->string('alt_text')->nullable();
            $table->string('filename');
            $table->string('filepath', 500);
            $table->string('disk', 30)->default('public');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size'); // Bytes
            $table->json('dimensions')->nullable(); // width, height, thumbnails
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
