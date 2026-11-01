<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Public/Home', [
            'featuredCourses' => Course::query()
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->with('category', 'teacher.user')
                ->latest('published_at')
                ->take(3)
                ->get(),
            'categories' => Category::where('is_active', true)->get(['id', 'name', 'slug']),
        ]);
    }
}