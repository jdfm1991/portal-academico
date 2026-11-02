<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Deshabilita Vite durante las pruebas para evitar el error del manifest
        $this->withoutVite();
    }

    public function test_un_estudiante_puede_registrarse(): void
    {
        $response = $this->post('/register', [
            'name' => 'Estudiante Test',
            'email' => 'estudiante@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/mi-cuenta/cursos');
        $this->assertDatabaseHas('users', [
            'email' => 'estudiante@test.com',
            'role' => 'student',
        ]);
        $this->assertAuthenticated();
    }

    public function test_no_se_puede_registrar_con_email_duplicado(): void
    {
        User::factory()->create(['email' => 'existente@test.com']);

        $response = $this->post('/register', [
            'name' => 'Otro Usuario',
            'email' => 'existente@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_un_usuario_puede_iniciar_sesion(): void
    {
        $user = User::factory()->create([
            'email' => 'login@test.com',
            'password' => bcrypt('password123'),
            'role' => 'student',
        ]);

        $response = $this->post('/login', [
            'email' => 'login@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/mi-cuenta/cursos');
        $this->assertAuthenticatedAs($user);
    }

    public function test_un_admin_es_redirigido_al_panel_al_iniciar_sesion(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_credenciales_incorrectas_muestran_error(): void
    {
        User::factory()->create([
            'email' => 'existe@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'existe@test.com',
            'password' => 'incorrecta',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_un_usuario_no_verificado_no_puede_acceder_al_portal(): void
    {
        $user = User::factory()->create([
            'role' => 'student',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/mi-cuenta/cursos');

        $response->assertRedirect('/verify-email');
    }

    public function test_un_usuario_verificado_puede_acceder_al_portal(): void
    {
        $user = User::factory()->create([
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/mi-cuenta/cursos');

        $response->assertOk();
    }
}
