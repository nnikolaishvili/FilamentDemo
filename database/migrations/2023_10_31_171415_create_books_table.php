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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->foreignId('publisher_id')->constrained()->cascadeOnDelete();
            $table->timestamp('publication_date');
            $table->string('thumbnail')->nullable();
            $table->json('tags')->nullable();
            $table->decimal('price');
            $table->boolean('is_best_seller')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
