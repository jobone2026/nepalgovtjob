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
        Schema::table('posts', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('notification_date');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('online_form', 500)->nullable()->after('end_date');
            $table->string('salary', 255)->nullable()->after('total_posts');
            $table->string('final_result', 500)->nullable()->after('online_form');
            $table->boolean('is_upcoming')->default(false)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'online_form', 'salary', 'final_result', 'is_upcoming']);
        });
    }
};
