<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseDetailController extends Controller
{
    public function __invoke(string $slug)
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['category', 'teacher.user'])
            ->firstOrFail();

        $hasEnrollment = false;
        if (auth()->check()) {
            $hasEnrollment = Enrollment::where('student_id', auth()->id())
                ->where('course_id', $course->id)
                ->exists();
        }

        return Inertia::render('Public/Courses/Show', [
            'course' => $course,
            'hasEnrollment' => $hasEnrollment,
        ]);
    }
}
