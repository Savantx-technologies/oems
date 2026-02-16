<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->integer('total_questions')->default(0);
            $table->integer('total_correct')->default(0);
            $table->decimal('score', 8, 2)->default(0);

            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'user_id']);
            $table->index(['exam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
