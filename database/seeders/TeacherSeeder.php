<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Los 3 usuarios con rol teacher ya existen; creamos sus perfiles
        $teacherUsers = User::where('role', 'teacher')->get();

        foreach ($teacherUsers as $user) {
            Teacher::factory()->create(['user_id' => $user->id]);
        }
    }
}