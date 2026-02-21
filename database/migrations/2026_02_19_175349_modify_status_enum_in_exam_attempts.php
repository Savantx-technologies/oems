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
       DB::statement("
        ALTER TABLE exam_attempts 
        MODIFY status ENUM(
            'in_progress',
            'submitted',
            'evaluated',
            'approved',
            'rejected',
            'expired',
            'terminated'
        ) NOT NULL DEFAULT 'in_progress'
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
        ALTER TABLE exam_attempts 
        MODIFY status ENUM(
            'in_progress',
            'submitted',
            'expired',
            'terminated'
        ) NOT NULL DEFAULT 'in_progress'
    ");
    }
};
