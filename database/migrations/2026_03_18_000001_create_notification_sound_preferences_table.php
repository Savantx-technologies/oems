<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_sound_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notifiable_type');
            $table->string('tone')->default('chime');
            $table->string('custom_sound_path')->nullable();
            $table->string('custom_sound_name')->nullable();
            $table->timestamps();

            $table->unique(['notifiable_id', 'notifiable_type'], 'notification_sound_preferences_unique');
            $table->index(['notifiable_type', 'notifiable_id'], 'notification_sound_preferences_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_sound_preferences');
    }
};
