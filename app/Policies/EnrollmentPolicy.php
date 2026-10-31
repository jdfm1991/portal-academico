<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'teacher'
            && $user->teacher?->id === $enrollment->course->teacher_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        // El teacher no puede cambiar estados, solo ver
        return false;
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->role === 'admin';
    }
}
