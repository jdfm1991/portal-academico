<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin principal (credenciales conocidas para pruebas)
        User::factory()->admin()->create([
            'name' => 'Admin Principal',
            'email' => 'admin@portal.test',
        ]);

        // Profesores
        User::factory()->teacher()->count(3)->create();

        // Estudiantes
        User::factory()->student()->count(10)->create();
    }
}