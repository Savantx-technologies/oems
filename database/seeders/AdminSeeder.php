<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a School
        $schoolId = DB::table('schools')->insertGetId([
            'name' => 'Demo Public School',
            'code' => 'DPS001',
            'type' => 'Public',
            'board' => 'CBSE',
            'registration_no' => 'REG-2026-001',
            'established_year' => 2000,
            'address' => '123 Education Lane',
            'city' => 'Tech City',
            'state' => 'Delhi',
            'pincode' => '110001',
            'country' => 'India',
            'contact_number' => '9876543210',
            'email' => 'info@dps.demo',
            'is_active' => 1,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create School Admin linked to the School
        DB::table('admins')->insert([
            'school_id' => $schoolId,
            'name' => 'School Administrator',
            'email' => 'admin1@gmail.com',
            'mobile' => '9876543211',
            'password' => Hash::make('12345678'),
            'role' => 'school_admin',
            'status' => 'active',
            'login_method' => 'password',
            'two_factor' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create a Super Admin in the admins table
        DB::table('admins')->insert([
            'school_id' => null,
            'name' => 'Super Administrator',
            'email' => 'admin@gmail.com',
            'mobile' => '9876543212',
            'password' => Hash::make('12345678'),
            'role' => 'superadmin',
            'status' => 'active',
            'login_method' => 'password',
            'two_factor' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }   
}
