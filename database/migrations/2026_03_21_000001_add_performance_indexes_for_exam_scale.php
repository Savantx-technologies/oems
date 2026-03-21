<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index(['school_id', 'grade', 'role'], 'users_school_grade_role_idx');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->index(['school_id', 'class', 'status', 'exam_type'], 'exams_school_class_status_type_idx');
        });

        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->index(['user_id', 'exam_id'], 'exam_attempts_user_exam_idx');
            $table->index(['exam_id', 'status'], 'exam_attempts_exam_status_idx');
            $table->index(['status', 'last_activity_at'], 'exam_attempts_status_activity_idx');
        });
            
        Schema::table('user_exam_answers', function (Blueprint $table) {
            $table->index(['attempt_id', 'question_id'], 'user_exam_answers_attempt_question_idx');
            $table->index(['exam_id', 'user_id'], 'user_exam_answers_exam_user_idx');
        });

        Schema::table('exam_violations', function (Blueprint $table) {
            $table->index(['attempt_id', 'occurred_at'], 'exam_violations_attempt_occurred_idx');
        });

        Schema::table('exam_streams', function (Blueprint $table) {
            $table->index(['attempt_id', 'status'], 'exam_streams_attempt_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('exam_streams', function (Blueprint $table) {
            $table->dropIndex('exam_streams_attempt_status_idx');
        });

        Schema::table('exam_violations', function (Blueprint $table) {
            $table->dropIndex('exam_violations_attempt_occurred_idx');
        });

        Schema::table('user_exam_answers', function (Blueprint $table) {
            $table->dropIndex('user_exam_answers_attempt_question_idx');
            $table->dropIndex('user_exam_answers_exam_user_idx');
        });

        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropIndex('exam_attempts_user_exam_idx');
            $table->dropIndex('exam_attempts_exam_status_idx');
            $table->dropIndex('exam_attempts_status_activity_idx');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropIndex('exams_school_class_status_type_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_school_grade_role_idx');
        });
    }
};
