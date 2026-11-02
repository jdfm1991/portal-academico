<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(string $slug)
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $user = Auth::user();

        // Verificar si ya existe una inscripción (incluso cancelada o rechazada)
        $existing = Enrollment::where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return back()->withErrors([
                'enrollment' => 'Ya tienes una solicitud para este curso.',
            ]);
        }

        // Crear la inscripción
        Enrollment::create([
            'student_id' => $user->id,
            'course_id' => $course->id,
            'enrollment_status' => 'pending',
            'payment_status' => $course->price > 0 ? 'pending' : 'not_required',
            'requested_at' => now(),
        ]);

        return redirect()->route('courses.show', $course->slug)
            ->with('status', 'Solicitud de inscripción enviada correctamente.');
    }
}
