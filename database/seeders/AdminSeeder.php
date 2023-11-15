<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admiData = [
            'name' => 'Super admin', 'email' => config('app.admin_email'), 'password' => config('app.admin_password'),
        ];

        Admin::firstOrCreate(['email' => $admiData['email']], $admiData);
    }
}
