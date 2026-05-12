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
            $table->date('age_as_on_date')->nullable()->after('age_max_ex_serviceman');
            $table->smallInteger('fee_women')->unsigned()->nullable()->after('fee_ex_serviceman');
            $table->string('fee_payment_mode', 255)->nullable()->after('fee_note');
            $table->string('pay_scale_level', 255)->nullable()->after('salary_display_label');
            $table->smallInteger('recruitment_year')->unsigned()->nullable()->after('notification_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'age_as_on_date',
                'fee_women',
                'fee_payment_mode',
                'pay_scale_level',
                'recruitment_year'
            ]);
        });
    }
};
