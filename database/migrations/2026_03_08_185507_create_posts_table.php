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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->enum('type', ['job', 'admit_card', 'syllabus', 'result', 'answer_key', 'blog']);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('state_id')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('content');
            $table->unsignedInteger('total_posts')->nullable();
            $table->dateTime('last_date')->nullable();
            $table->dateTime('notification_date')->nullable();
            $table->json('important_links')->nullable();
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            
            $table->index('type');
            $table->index('is_published');
            $table->index('created_at');
            $table->index('category_id');
            $table->index('state_id');
            $table->index('slug');
            $table->index('is_featured');
            $table->index(['type', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
