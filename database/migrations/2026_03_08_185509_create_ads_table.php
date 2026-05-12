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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->enum('position', ['header', 'sidebar', 'after_post', 'footer']);
            $table->enum('type', ['adsense', 'custom']);
            $table->text('code')->nullable();
            $table->json('adsense_slot_ids')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('position');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
