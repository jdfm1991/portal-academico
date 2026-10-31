<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseMaterialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_id' => Course::factory()->published(),
            'title' => $this->faker->sentence(3),
            'type' => 'link',
            'file_path' => null,
            'external_url' => $this->faker->url(),
        ];
    }

    public function file(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'file',
            'file_path' => 'materials/' . fake()->uuid() . '.pdf',
            'external_url' => null,
        ]);
    }

    public function link(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'link',
            'file_path' => null,
            'external_url' => fake()->url(),
        ]);
    }
}