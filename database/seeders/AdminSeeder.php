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
                'first_name' => 'Ruwilson',
                'last_name'  => 'Hipos',
                'email'      => 'admin@gmail.com',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
