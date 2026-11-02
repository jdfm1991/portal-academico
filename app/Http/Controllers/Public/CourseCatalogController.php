<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with('category', 'teacher.user');

        // Filtro por categoría (slug)
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtro por modalidad
        if ($request->filled('modality')) {
            $query->where('modality', $request->modality);
        }

        // Filtro por nivel
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filtro por rango de precio
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Búsqueda por título
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->boolean('free')) {
            $query->where('price', 0);
        }

        $courses = $query
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString()
            ->through(fn($course) => $course->append('image_url'));

        return Inertia::render('Public/Courses/Index', [
            'courses' => $courses,
            'categories' => Category::where('is_active', true)->get(['id', 'name', 'slug']),
            'filters' => $request->only(['category', 'modality', 'level', 'min_price', 'max_price', 'search', 'free']),
        ]);
    }
}
