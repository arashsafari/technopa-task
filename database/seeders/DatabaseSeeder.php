<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => config('settings.technopay.admin_email'),
            'phone_number' => config('settings.technopay.admin_phone_number'),
            'national_code' =>  config('settings.technopay.admin_national_code'),
        ]);

        $this->call([
            OrderSeeder::class,
        ]);
    }
}
