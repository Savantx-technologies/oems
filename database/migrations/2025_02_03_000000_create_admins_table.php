<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->string('password');
            $table->enum('role', ['school_admin', 'sub_admin', 'invigilator', 'staff'])->default('school_admin');
            $table->enum('status', ['active', 'blocked', 'pending'])->default('pending');

            // Aadhaar / Govt info
            $table->string('aadhaar_number')->nullable(); // Consider encrypting
            $table->string('aadhaar_name')->nullable();
            $table->date('aadhaar_dob')->nullable();
            $table->enum('aadhaar_gender', ['male','female','other'])->nullable();

            // Two-factor, login method etc.
            $table->boolean('two_factor')->default(true);
            $table->enum('login_method', ['password','otp'])->default('password');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
