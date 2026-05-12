<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add comprehensive job fields for AI agent system
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // ── Age limits ────────────────────────────────────────────────
            $table->tinyInteger('age_min')->unsigned()->nullable()->after('salary');
            $table->tinyInteger('age_max_gen')->unsigned()->nullable()->after('age_min');
            $table->tinyInteger('age_max_obc')->unsigned()->nullable()->after('age_max_gen');
            $table->tinyInteger('age_max_sc')->unsigned()->nullable()->after('age_max_obc');
            $table->tinyInteger('age_max_st')->unsigned()->nullable()->after('age_max_sc');
            $table->tinyInteger('age_max_ews')->unsigned()->nullable()->after('age_max_st');
            $table->tinyInteger('age_max_ph')->unsigned()->nullable()->after('age_max_ews');
            $table->tinyInteger('age_max_ex_serviceman')->unsigned()->nullable()->after('age_max_ph');
            $table->string('age_relaxation_note', 500)->nullable()->after('age_max_ex_serviceman');

            // ── Application fee ───────────────────────────────────────────
            $table->smallInteger('fee_general')->unsigned()->nullable()->after('age_relaxation_note');
            $table->smallInteger('fee_obc')->unsigned()->nullable()->after('fee_general');
            $table->smallInteger('fee_sc_st')->unsigned()->nullable()->after('fee_obc');
            $table->smallInteger('fee_ph')->unsigned()->nullable()->after('fee_sc_st');
            $table->smallInteger('fee_ex_serviceman')->unsigned()->nullable()->after('fee_ph');
            $table->string('fee_note', 500)->nullable()->after('fee_ex_serviceman');

            // ── Category-wise vacancy ─────────────────────────────────────
            $table->smallInteger('vacancy_gen')->unsigned()->nullable()->after('total_posts');
            $table->smallInteger('vacancy_obc')->unsigned()->nullable()->after('vacancy_gen');
            $table->smallInteger('vacancy_sc')->unsigned()->nullable()->after('vacancy_obc');
            $table->smallInteger('vacancy_st')->unsigned()->nullable()->after('vacancy_sc');
            $table->smallInteger('vacancy_ews')->unsigned()->nullable()->after('vacancy_st');
            $table->smallInteger('vacancy_ph')->unsigned()->nullable()->after('vacancy_ews');
            $table->smallInteger('vacancy_ex_serviceman')->unsigned()->nullable()->after('vacancy_ph');
            $table->string('vacancy_breakdown_note', 500)->nullable()->after('vacancy_ex_serviceman');

            // ── Job nature / type ─────────────────────────────────────────
            $table->enum('job_nature', ['permanent', 'contractual', 'trainee', 'deputation', 'ad-hoc'])->default('permanent')->after('type');
            $table->enum('salary_type', ['salary', 'stipend', 'consolidated', 'pay_scale'])->default('salary')->after('salary');
            $table->string('salary_display_label', 255)->nullable()->after('salary_type');
            $table->string('post_training_pay', 500)->nullable()->after('salary_display_label');
            $table->tinyInteger('experience_years')->unsigned()->default(0)->after('post_training_pay');
            $table->enum('experience_type', ['fresher', 'experienced', 'both'])->default('fresher')->after('experience_years');

            // ── Lifecycle dates ───────────────────────────────────────────
            $table->date('exam_date')->nullable()->after('last_date');
            $table->date('admit_card_date')->nullable()->after('exam_date');
            $table->date('result_date')->nullable()->after('admit_card_date');
            $table->date('interview_date')->nullable()->after('result_date');
            $table->date('dv_date')->nullable()->after('interview_date');

            // ── Content quality ───────────────────────────────────────────
            $table->string('post_name', 255)->nullable()->after('title');
            $table->string('seo_title', 255)->nullable()->after('post_name');
            $table->json('selection_stages')->nullable()->after('seo_title');
            $table->enum('scope', ['all_india', 'state_specific'])->default('state_specific')->after('state_id');
            $table->string('sub_category', 100)->nullable()->after('category_id');
            
            // ── Organisation details ──────────────────────────────────────
            $table->string('organisation_full', 500)->nullable()->after('organization');
            $table->string('organisation_type', 100)->nullable()->after('organisation_full');
            $table->string('advt_no', 255)->nullable()->after('organisation_type');
            $table->string('department', 255)->nullable()->after('advt_no');
            
            // ── Education details ─────────────────────────────────────────
            $table->enum('education_level', ['10th', '12th', 'diploma', 'graduate', 'post_graduate', 'phd'])->nullable()->after('education');
            $table->string('education_note', 1000)->nullable()->after('education_level');
            $table->json('education_specialisation')->nullable()->after('education_note');
            
            // ── Schema fields ─────────────────────────────────────────────
            $table->string('post_name_schema', 255)->nullable()->after('education_specialisation');
            $table->string('hiring_org_schema', 500)->nullable()->after('post_name_schema');
            $table->string('hiring_org_url', 500)->nullable()->after('hiring_org_schema');
            $table->enum('employment_type_schema', ['FULL_TIME', 'PART_TIME', 'CONTRACTOR', 'TEMPORARY', 'OTHER'])->default('FULL_TIME')->after('hiring_org_url');
            $table->enum('work_location_type', ['TELECOMMUTE', 'TELECOMMUTE_NOT_ELIGIBLE'])->default('TELECOMMUTE_NOT_ELIGIBLE')->after('employment_type_schema');
            $table->string('posting_location', 500)->nullable()->after('work_location_type');
            
            // ── Exam details ──────────────────────────────────────────────
            $table->enum('exam_mode', ['online', 'offline', 'both'])->nullable()->after('posting_location');
            $table->enum('exam_type', ['cbt', 'written', 'interview_only', 'document_verification'])->nullable()->after('exam_mode');
            
            // ── URLs ──────────────────────────────────────────────────────
            $table->string('notification_pdf_url', 1000)->nullable()->after('exam_type');
            $table->string('apply_url', 1000)->nullable()->after('notification_pdf_url');
            $table->boolean('direct_apply')->default(false)->after('apply_url');
            $table->string('official_website', 500)->nullable()->after('direct_apply');
            
            // ── Salary range ──────────────────────────────────────────────
            $table->integer('salary_min')->unsigned()->nullable()->after('salary');
            $table->integer('salary_max')->unsigned()->nullable()->after('salary_min');
            $table->string('salary_currency', 10)->default('INR')->after('salary_max');
            $table->enum('salary_period', ['HOUR', 'DAY', 'WEEK', 'MONTH', 'YEAR'])->default('MONTH')->after('salary_currency');
            
            // ── Additional content fields ─────────────────────────────────
            $table->text('qualifications')->nullable()->after('content');
            $table->text('skills')->nullable()->after('qualifications');
            $table->text('responsibilities')->nullable()->after('skills');
            $table->json('faq')->nullable()->after('responsibilities');
            
            // ── Data quality tracking ─────────────────────────────────────
            $table->json('validation_issues')->nullable()->after('faq');
            $table->boolean('needs_manual_review')->default(false)->after('validation_issues');
            $table->float('ai_confidence_score')->nullable()->after('needs_manual_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'age_min', 'age_max_gen', 'age_max_obc', 'age_max_sc', 'age_max_st', 
                'age_max_ews', 'age_max_ph', 'age_max_ex_serviceman', 'age_relaxation_note',
                'fee_general', 'fee_obc', 'fee_sc_st', 'fee_ph', 'fee_ex_serviceman', 'fee_note',
                'vacancy_gen', 'vacancy_obc', 'vacancy_sc', 'vacancy_st', 'vacancy_ews', 
                'vacancy_ph', 'vacancy_ex_serviceman', 'vacancy_breakdown_note',
                'job_nature', 'salary_type', 'salary_display_label', 'post_training_pay',
                'experience_years', 'experience_type',
                'exam_date', 'admit_card_date', 'result_date', 'interview_date', 'dv_date',
                'post_name', 'seo_title', 'selection_stages', 'scope', 'sub_category',
                'organisation_full', 'organisation_type', 'advt_no', 'department',
                'education_level', 'education_note', 'education_specialisation',
                'post_name_schema', 'hiring_org_schema', 'hiring_org_url', 
                'employment_type_schema', 'work_location_type', 'posting_location',
                'exam_mode', 'exam_type',
                'notification_pdf_url', 'apply_url', 'direct_apply', 'official_website',
                'salary_min', 'salary_max', 'salary_currency', 'salary_period',
                'qualifications', 'skills', 'responsibilities', 'faq',
                'validation_issues', 'needs_manual_review', 'ai_confidence_score'
            ]);
        });
    }
};
