<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('exam_attempts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // tab_switch, fullscreen_exit, screen_stop
            $table->timestamp('occurred_at');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_violations');
    }
};
