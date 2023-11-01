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
        Schema::table('author_book', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->foreignId('role_id')->after('book_id')->constrained('author_roles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('author_book', function (Blueprint $table) {
            //
        });
    }
};
