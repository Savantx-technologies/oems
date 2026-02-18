<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->unsignedBigInteger('school_id');

            $table->string('title');
            $table->string('class');
            $table->string('subject');

            $table->integer('total_marks')->default(0);
            $table->integer('duration_minutes');

            $table->text('instructions')->nullable();

            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');

            $table->unsignedBigInteger('created_by'); // admin id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
