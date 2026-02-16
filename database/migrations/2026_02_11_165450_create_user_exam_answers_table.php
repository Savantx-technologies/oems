<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_exam_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('attempt_id')
                  ->constrained('exam_attempts')
                  ->onDelete('cascade');

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('exam_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('question_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('selected_option')->nullable();

            $table->boolean('is_correct')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'exam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_exam_answers');
    }
};
