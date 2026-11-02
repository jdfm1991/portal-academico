<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_estudiante_no_puede_acceder_al_panel(): void
    {
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);

        $response = $this->actingAs($student)->get('/admin');

        $response->assertForbidden(); // 403
    }

    public function test_un_admin_puede_acceder_al_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk(); // El dashboard se renderiza directamente
    }

    public function test_un_teacher_no_puede_crear_cursos(): void
    {
        $teacherUser = User::factory()->create(['role' => 'teacher']);
        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'specialty' => 'Test',
            'is_active' => true,
        ]);

        $this->actingAs($teacherUser);

        $this->assertFalse($teacherUser->can('create', Course::class));
    }

    public function test_un_teacher_solo_puede_actualizar_sus_propios_cursos(): void
    {
        $teacherUser1 = User::factory()->create(['role' => 'teacher']);
        $teacher1 = Teacher::create(['user_id' => $teacherUser1->id, 'is_active' => true]);

        $teacherUser2 = User::factory()->create(['role' => 'teacher']);
        $teacher2 = Teacher::create(['user_id' => $teacherUser2->id, 'is_active' => true]);

        $category = Category::create(['name' => 'Test', 'slug' => 'test', 'is_active' => true]);

        $course1 = Course::create([
            'category_id' => $category->id,
            'teacher_id' => $teacher1->id,
            'title' => 'Curso de Teacher 1',
            'slug' => 'curso-teacher-1',
            'description' => 'Test',
            'price' => 100,
            'modality' => 'online',
            'level' => 'principiante',
            'status' => 'draft',
        ]);

        $course2 = Course::create([
            'category_id' => $category->id,
            'teacher_id' => $teacher2->id,
            'title' => 'Curso de Teacher 2',
            'slug' => 'curso-teacher-2',
            'description' => 'Test',
            'price' => 100,
            'modality' => 'online',
            'level' => 'principiante',
            'status' => 'draft',
        ]);

        $this->actingAs($teacherUser1);

        // Puede actualizar su propio curso
        $this->assertTrue($teacherUser1->can('update', $course1));

        // No puede actualizar el curso del otro
        $this->assertFalse($teacherUser1->can('update', $course2));
    }

    public function test_un_admin_puede_actualizar_cualquier_curso(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $teacherUser = User::factory()->create(['role' => 'teacher']);
        $teacher = Teacher::create(['user_id' => $teacherUser->id, 'is_active' => true]);

        $category = Category::create(['name' => 'Test', 'slug' => 'test', 'is_active' => true]);

        $course = Course::create([
            'category_id' => $category->id,
            'teacher_id' => $teacher->id,
            'title' => 'Curso Test',
            'slug' => 'curso-test',
            'description' => 'Test',
            'price' => 100,
            'modality' => 'online',
            'level' => 'principiante',
            'status' => 'draft',
        ]);

        $this->actingAs($admin);

        $this->assertTrue($admin->can('update', $course));
        $this->assertTrue($admin->can('delete', $course));
    }
}
