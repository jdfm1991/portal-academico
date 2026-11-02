<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CourseMaterialController extends Controller
{
    public function index(string $slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        $enrollment = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('enrollment_status', 'approved')
            ->first();

        abort_if(!$enrollment, 403, 'No tienes acceso a los materiales de este curso.');

        $materials = $course->materials()
            ->where(function ($query) {
                $query->whereNull('visible_from')
                      ->orWhere('visible_from', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('visible_until')
                      ->orWhere('visible_until', '>=', now());
            })
            ->latest()
            ->get();

        $materials = $materials->map(function ($material) {
            if ($material->type === 'file' && $material->file_path) {
                $material->temporary_url = Storage::disk('local')->temporaryUrl(
                    $material->file_path,
                    now()->addMinutes(30)
                );
            }
            return $material;
        });

        return Inertia::render('Student/Materials/Index', [
            'course' => $course,
            'materials' => $materials,
        ]);
    }
}