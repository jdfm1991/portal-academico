<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    public function view(User $user, Course $course): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'teacher' && $user->teacher?->id === $course->teacher_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Course $course): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'teacher' && $user->teacher?->id === $course->teacher_id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->role === 'admin';
    }
}