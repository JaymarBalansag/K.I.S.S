<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@gmail.com'], // prevent duplicates
            [
                'first_name' => 'Madilyn',
                'last_name'  => 'Merano',
                'email'      => 'madilyn@gmail.com',
                'password'   => Hash::make('admin_madilyn'),
                'role'       => 'admin',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('users')->updateOrInsert(
            ['email' => 'gabriel@gmail.com'], // prevent duplicates
            [
                'first_name' => 'Gabriel',
                'last_name'  => 'Realino',
                'email'      => 'gabriel@gmail.com',
                'password'   => Hash::make('staff_gabriel'),
                'role'       => 'staff',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('users')->updateOrInsert(
            ['email' => 'roseann@gmail.com'], // prevent duplicates
            [
                'first_name' => 'Roseann',
                'last_name'  => 'Bangcale',
                'email'      => 'roseann@gmail.com',
                'password'   => Hash::make('staff_roseann'),
                'role'       => 'staff',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
