<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(CanchaSeeder::class);

        \App\Models\User::factory()->create(
            [
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('070893'), // password
            ]
        )->assignRole('Admin');

        User::factory()->count(10)->create();
    }
}
