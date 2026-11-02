<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    private function createPublishedCourse(): Course
    {
        $teacherUser = User::factory()->create(['role' => 'teacher']);
        $teacher = Teacher::create(['user_id' => $teacherUser->id, 'is_active' => true]);
        $category = Category::create(['name' => 'Test', 'slug' => 'test', 'is_active' => true]);

        return Course::create([
            'category_id' => $category->id,
            'teacher_id' => $teacher->id,
            'title' => 'Curso de Prueba',
            'slug' => 'curso-de-prueba',
            'description' => 'Descripción',
            'price' => 100,
            'modality' => 'online',
            'level' => 'principiante',
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function test_un_estudiante_puede_inscribirse_en_un_curso_publicado(): void
    {
        $course = $this->createPublishedCourse();
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);

        $response = $this->actingAs($student)->post("/cursos/{$course->slug}/inscribirse");

        $response->assertRedirect("/cursos/{$course->slug}");
        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    public function test_un_estudiante_no_puede_inscribirse_dos_veces_en_el_mismo_curso(): void
    {
        $course = $this->createPublishedCourse();
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);

        // Primera inscripción
        $this->actingAs($student)->post("/cursos/{$course->slug}/inscribirse");

        // Segunda inscripción
        $response = $this->actingAs($student)->post("/cursos/{$course->slug}/inscribirse");

        $response->assertSessionHasErrors('enrollment');
        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_no_se_puede_marcar_pago_pagado_en_inscripcion_rechazada(): void
    {
        $course = $this->createPublishedCourse();
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);

        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_status' => 'rejected',
            'payment_status' => 'pending',
        ]);

        $this->expectException(\InvalidArgumentException::class);

        $enrollment->update(['payment_status' => 'paid']);
    }

    public function test_un_curso_en_borrador_no_es_accesible_publicamente(): void
    {
        $course = $this->createPublishedCourse();
        $course->update(['status' => 'draft', 'published_at' => null]);

        $response = $this->get("/cursos/{$course->slug}");

        $response->assertNotFound(); // 404
    }

    public function test_un_estudiante_no_puede_inscribirse_en_un_curso_en_borrador(): void
    {
        $course = $this->createPublishedCourse();
        $course->update(['status' => 'draft', 'published_at' => null]);

        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);

        $response = $this->actingAs($student)->post("/cursos/{$course->slug}/inscribirse");

        $response->assertNotFound();
        $this->assertDatabaseCount('enrollments', 0);
    }
}