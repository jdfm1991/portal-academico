<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Database\Seeder;

class CourseMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $publishedCourses = Course::where('status', 'published')->get();

        foreach ($publishedCourses as $course) {
            // 2 materiales tipo enlace por curso
            CourseMaterial::factory()->count(2)->link()->create([
                'course_id' => $course->id,
            ]);

            // 1 material tipo archivo por curso (simulado, sin archivo real)
            CourseMaterial::factory()->file()->create([
                'course_id' => $course->id,
            ]);
        }
    }
}