<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Age
            if (!Schema::hasColumn('posts', 'age_as_on_date')) {
                $table->date('age_as_on_date')->nullable()->after('age_max_ex_serviceman');
            }
            
            // Fees
            if (!Schema::hasColumn('posts', 'fee_women')) {
                $table->smallInteger('fee_women')->unsigned()->nullable()->after('fee_sc_st');
            }
            
            // Vacancy Breakdown
            $vacancyFields = [
                'vacancy_gen' => 'total_posts',
                'vacancy_obc' => 'vacancy_gen',
                'vacancy_sc' => 'vacancy_obc',
                'vacancy_st' => 'vacancy_sc',
                'vacancy_ews' => 'vacancy_st',
                'vacancy_ph' => 'vacancy_ews',
                'vacancy_ex_serviceman' => 'vacancy_ph'
            ];
            foreach ($vacancyFields as $col => $after) {
                if (!Schema::hasColumn('posts', $col)) {
                    $table->smallInteger($col)->unsigned()->nullable()->after($after);
                }
            }
            
            if (!Schema::hasColumn('posts', 'vacancy_breakdown_note')) {
                $table->string('vacancy_breakdown_note', 500)->nullable()->after('vacancy_ex_serviceman');
            }

            // Selection
            if (!Schema::hasColumn('posts', 'selection_stages')) {
                $table->json('selection_stages')->nullable()->after('seo_title');
            }
            
            // Rich Content (if missing)
            $richFields = ['qualifications', 'skills', 'responsibilities', 'faq', 'validation_issues'];
            foreach ($richFields as $col) {
                if (!Schema::hasColumn('posts', $col)) {
                    if ($col === 'faq' || $col === 'validation_issues') {
                        $table->json($col)->nullable();
                    } else {
                        $table->text($col)->nullable();
                    }
                }
            }
        });
    }

    public function down(): void
    {
        // No down needed for repair
    }
};
