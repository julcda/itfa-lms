<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCourses = Course::where('status', 'published')
            ->where('is_featured', true)
            ->with('teacher', 'category')
            ->take(6)
            ->get();

        $latestBooks = Book::where('status', 'active')
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('featuredCourses', 'latestBooks'));
    }
}
