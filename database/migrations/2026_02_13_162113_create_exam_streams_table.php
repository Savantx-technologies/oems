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
 
            $table->foreignId('attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
 
            // Viewer details
            $table->morphs('viewer'); // Adds viewer_id and viewer_type
            $table->string('viewer_session_id')->unique(); // Unique ID for each viewer tab
 
            // SDP data
            $table->longText('offer')->nullable(); // Student's offer to this viewer
            $table->longText('answer')->nullable(); // Viewer's answer
 
            // ICE candidates
            $table->longText('student_ice_candidates')->nullable();
            $table->longText('viewer_ice_candidates')->nullable();
 
            // Connection status
            $table->enum('status', ['requesting', 'offer_sent', 'connected', 'disconnected'])->default('requesting');
 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_streams');
    }
};