<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id');
        $teachers = Teacher::pluck('id');

        if ($categories->isEmpty() || $teachers->isEmpty()) {
            $this->command->error('Necesitas categorías y profesores antes de crear cursos.');
            return;
        }

        // 10 cursos publicados (visibles en catálogo)
        Course::factory()->count(10)->published()->create([
            'category_id' => fn () => $categories->random(),
            'teacher_id' => fn () => $teachers->random(),
        ]);

        // 3 cursos en borrador (no visibles)
        Course::factory()->count(3)->draft()->create([
            'category_id' => fn () => $categories->random(),
            'teacher_id' => fn () => $teachers->random(),
        ]);

        // 2 cursos inactivos (no visibles)
        Course::factory()->count(2)->inactive()->create([
            'category_id' => fn () => $categories->random(),
            'teacher_id' => fn () => $teachers->random(),
        ]);
    }
}