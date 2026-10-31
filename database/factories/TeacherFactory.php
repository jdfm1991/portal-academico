<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->teacher(),
            'specialty' => $this->faker->randomElement([
                'Desarrollo Web',
                'Diseño UX/UI',
                'Inglés',
                'Marketing Digital',
                'Gestión de Proyectos',
            ]),
            'biography' => $this->faker->paragraph(3),
            'photo_path' => null,
            'is_active' => true,
        ];
    }
}