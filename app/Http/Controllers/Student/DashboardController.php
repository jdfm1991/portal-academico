<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::query()
            ->where('student_id', Auth::id())
            ->with(['course.category', 'course.teacher.user', 'receipts'])
            ->latest('requested_at')
            ->get();

        return Inertia::render('Student/Courses/Index', [
            'enrollments' => $enrollments,
        ]);
    }
}