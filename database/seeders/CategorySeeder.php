<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Programación', 'description' => 'Cursos de desarrollo de software, lenguajes y frameworks.'],
            ['name' => 'Diseño Gráfico', 'description' => 'Diseño visual, UX/UI y herramientas creativas.'],
            ['name' => 'Idiomas', 'description' => 'Aprendizaje de inglés, francés, portugués y más.'],
            ['name' => 'Negocios', 'description' => 'Gestión, administración y emprendimiento.'],
            ['name' => 'Marketing Digital', 'description' => 'SEO, redes sociales, publicidad y estrategia digital.'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }
}