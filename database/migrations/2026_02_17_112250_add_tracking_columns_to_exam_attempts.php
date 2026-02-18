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
        Schema::table('exam_attempts', function (Blueprint $table) {
            // Add ip_address if it doesn't exist
            if (!Schema::hasColumn('exam_attempts', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('status');
            }
            
            // Add last_activity_at for heartbeat tracking
            if (!Schema::hasColumn('exam_attempts', 'last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable()->after('ip_address');
            }

            // Add extra_time_seconds for time extensions
            if (!Schema::hasColumn('exam_attempts', 'extra_time_seconds')) {
                $table->integer('extra_time_seconds')->default(0)->after('last_activity_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'last_activity_at', 'extra_time_seconds']);
        });
    }
};
