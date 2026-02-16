<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_streams', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('attempt_id');
            $table->foreign('attempt_id')
                  ->references('id')
                  ->on('exam_attempts')
                  ->cascadeOnDelete();

            $table->longText('offer')->nullable();
            $table->longText('answer')->nullable();

            $table->longText('student_ice_candidates')->nullable();
            $table->longText('admin_ice_candidates')->nullable();

            $table->timestamps();

            $table->index('attempt_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_streams');
    }
};