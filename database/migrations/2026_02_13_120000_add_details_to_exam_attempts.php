<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('exam_attempts', 'status')) {
                $table->enum('status', ['in_progress', 'submitted', 'expired', 'terminated'])->default('in_progress')->after('score');
            }
            if (!Schema::hasColumn('exam_attempts', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('started_at');
            }
            if (!Schema::hasColumn('exam_attempts', 'question_order')) {
                $table->json('question_order')->nullable()->after('session_token');
            }
            if (!Schema::hasColumn('exam_attempts', 'terminated_reason')) {
                $table->string('terminated_reason')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropColumn(['status', 'expires_at', 'question_order', 'terminated_reason']);
        });
    }
};
