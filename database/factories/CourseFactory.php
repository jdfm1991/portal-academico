<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        // Título único (Faker garantiza no repetir en la misma ejecución)
        $title = $this->faker->unique()->sentence(4);

        // Slug: derivado del título + sufijo aleatorio amplio.
        // Usamos Str::random() en lugar de numberBetween() porque no tiene
        // límite superior y no agota su rango como unique()->numberBetween().
        $slug = Str::slug($title) . '-' . Str::lower(Str::random(6));

        $startDate = $this->faker->dateTimeBetween('now', '+2 months');
        $durationWeeks = $this->faker->numberBetween(4, 16);
        $endDate = (clone $startDate)->modify("+{$durationWeeks} weeks");

        return [
            'category_id' => Category::factory(),
            'teacher_id' => Teacher::factory(),
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph(4),
            'syllabus' => $this->faker->paragraph(6),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'modality' => $this->faker->randomElement(['presencial', 'online', 'hibrido']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'image_path' => null,
            'status' => 'draft',
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
            'published_at' => null,
        ]);
    }
}