<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('meta_title', 255)->nullable()->change();
            $table->string('meta_description', 500)->nullable()->change();
            $table->text('meta_keywords')->nullable()->change(); // Changed to text for > 1000 chars
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('meta_title', 60)->nullable()->change();
            $table->string('meta_description', 160)->nullable()->change();
            $table->string('meta_keywords', 1000)->nullable()->change();
        });
    }
};
