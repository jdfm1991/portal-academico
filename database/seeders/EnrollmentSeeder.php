<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $publishedCourses = Course::where('status', 'published')->get();
        $admin = User::where('role', 'admin')->first();

        // 5 inscripciones pendientes (esperando revisión)
        Enrollment::factory()->count(5)->create([
            'student_id' => fn () => $students->random()->id,
            'course_id' => fn () => $publishedCourses->random()->id,
        ]);

        // 3 inscripciones aprobadas con pago confirmado
        Enrollment::factory()->count(3)->approved()->create([
            'student_id' => fn () => $students->random()->id,
            'course_id' => fn () => $publishedCourses->random()->id,
            'reviewed_by' => $admin->id,
        ]);

        // 2 inscripciones rechazadas
        Enrollment::factory()->count(2)->rejected()->create([
            'student_id' => fn () => $students->random()->id,
            'course_id' => fn () => $publishedCourses->random()->id,
            'reviewed_by' => $admin->id,
        ]);

        // 1 inscripción cancelada por el estudiante
        Enrollment::factory()->cancelled()->create([
            'student_id' => $students->random()->id,
            'course_id' => $publishedCourses->random()->id,
        ]);

        // 2 inscripciones pendientes con pago ya declarado (esperando validación)
        Enrollment::factory()->count(2)->paid()->create([
            'student_id' => fn () => $students->random()->id,
            'course_id' => fn () => $publishedCourses->random()->id,
        ]);
    }
}