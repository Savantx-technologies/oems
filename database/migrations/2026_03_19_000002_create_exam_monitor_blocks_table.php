<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_monitor_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('assignee_type')->nullable();
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->timestamps();

            $table->index(['exam_id', 'assignee_type', 'assignee_id']);
        });

        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->foreignId('monitor_block_id')->nullable()->after('exam_id')->constrained('exam_monitor_blocks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('monitor_block_id');
        });

        Schema::dropIfExists('exam_monitor_blocks');
    }
};
