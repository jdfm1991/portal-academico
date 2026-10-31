<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Programación',
            'Diseño Gráfico',
            'Idiomas',
            'Negocios',
            'Marketing Digital',
            'Fotografía',
            'Música',
            'Cocina',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(12),
            'is_active' => true,
        ];
    }
}