<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\Enrollment;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class StudentPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

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

    private function createEnrollment(User $student, Course $course, string $status = 'pending', string $paymentStatus = 'pending'): Enrollment
    {
        return Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_status' => $status,
            'payment_status' => $paymentStatus,
        ]);
    }

    public function test_un_estudiante_solo_ve_sus_propias_inscripciones(): void
    {
        $course = $this->createPublishedCourse();
        $student1 = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);
        $student2 = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);

        $this->createEnrollment($student1, $course);
        $this->createEnrollment($student2, $course);

        $response = $this->actingAs($student1)->get('/mi-cuenta/cursos');

        $response->assertOk();
        $response->assertInertia(
            fn(Assert $page) =>
            $page->component('Student/Courses/Index')
                ->has('enrollments', 1) // Solo debe ver 1, no 2
        );
    }

    public function test_un_estudiante_no_puede_subir_comprobante_para_inscripcion_ajena(): void
    {
        $course = $this->createPublishedCourse();
        $student1 = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);
        $student2 = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);

        $enrollment1 = $this->createEnrollment($student1, $course);

        // Student2 intenta acceder al formulario de comprobante de la inscripción de Student1
        $response = $this->actingAs($student2)->get("/mi-cuenta/inscripciones/{$enrollment1->id}/comprobante");

        $response->assertForbidden(); // 403
    }

    public function test_un_estudiante_puede_subir_comprobante_si_pago_esta_pendiente(): void
    {
        Storage::fake('local');

        $course = $this->createPublishedCourse();
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);
        $enrollment = $this->createEnrollment($student, $course);

        $file = UploadedFile::fake()->image('comprobante.jpg');

        $response = $this->actingAs($student)->post("/mi-cuenta/inscripciones/{$enrollment->id}/comprobante", [
            'receipt' => $file,
        ]);

        $response->assertRedirect('/mi-cuenta/cursos');
        $this->assertDatabaseHas('payment_receipts', [
            'enrollment_id' => $enrollment->id,
        ]);
        Storage::disk('local')->assertExists('receipts/' . $file->hashName());
    }

    public function test_un_estudiante_no_puede_subir_comprobante_si_pago_no_esta_pendiente(): void
    {
        $course = $this->createPublishedCourse();
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);
        $enrollment = $this->createEnrollment($student, $course, 'approved', 'paid');

        $response = $this->actingAs($student)->get("/mi-cuenta/inscripciones/{$enrollment->id}/comprobante");

        $response->assertForbidden(); // 403
    }

    public function test_un_estudiante_con_inscripcion_aprobada_puede_ver_materiales(): void
    {
        $course = $this->createPublishedCourse();
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);
        $enrollment = $this->createEnrollment($student, $course, 'approved', 'paid');

        CourseMaterial::create([
            'course_id' => $course->id,
            'title' => 'Material Test',
            'type' => 'link',
            'external_url' => 'https://example.com',
        ]);

        $response = $this->actingAs($student)->get("/mi-cuenta/cursos/{$course->slug}/materiales");

        $response->assertOk();
        $response->assertInertia(
            fn(Assert $page) =>
            $page->component('Student/Materials/Index')
                ->has('materials', 1)
        );
    }

    public function test_un_estudiante_con_inscripcion_pendiente_no_puede_ver_materiales(): void
    {
        $course = $this->createPublishedCourse();
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);
        $this->createEnrollment($student, $course, 'pending', 'pending');

        $response = $this->actingAs($student)->get("/mi-cuenta/cursos/{$course->slug}/materiales");

        $response->assertForbidden(); // 403
    }
}
