<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('super_admins', function (Blueprint $table) {
            $table->string('role')->default('superadmin')->after('password');
            $table->json('permissions')->nullable()->after('role');
        });

        DB::table('super_admins')
            ->whereNull('role')
            ->orWhere('role', '')
            ->update(['role' => 'superadmin']);
    }

    public function down(): void
    {
        Schema::table('super_admins', function (Blueprint $table) {
            $table->dropColumn(['role', 'permissions']);
        });
    }
};
