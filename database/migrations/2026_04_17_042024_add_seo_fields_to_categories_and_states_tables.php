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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('meta_title', 160)->nullable()->after('color');
            $table->string('meta_description', 255)->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->longText('seo_content')->nullable()->after('meta_keywords'); // For bottom-of-page rank building content
        });

        Schema::table('states', function (Blueprint $table) {
            $table->string('meta_title', 160)->nullable()->after('slug');
            $table->string('meta_description', 255)->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->longText('seo_content')->nullable()->after('meta_keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'seo_content']);
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'seo_content']);
        });
    }
};
