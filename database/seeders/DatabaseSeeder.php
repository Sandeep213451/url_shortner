<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now()->toDateTimeString();
        $pwd = Hash::make('password');

        // Create SuperAdmin using raw SQL as required by the coding task
        DB::statement(
            "INSERT INTO users (name, email, password, role, company_id, created_at, updated_at) " .
            "VALUES ('Super Admin', 'superadmin@gmail.com', '{$pwd}', 'SuperAdmin', NULL, '{$now}', '{$now}')"
        );
    }
}
