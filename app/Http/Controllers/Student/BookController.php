<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::where('status', 'active')
            ->with('category')
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(fn($q2) => $q2->where('title', 'like', "%$s%")
                    ->orWhere('title_ar', 'like', "%$s%")
                    ->orWhere('author', 'like', "%$s%"));
            })
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('file_type'),   fn($q) => $q->where('file_type', $request->file_type))
            ->when($request->filled('grade_level'), fn($q) => $q->where('grade_level', $request->grade_level));

        $query = match($request->get('sort', 'latest')) {
            'popular'   => $query->orderByDesc('view_count'),
            'downloads' => $query->orderByDesc('download_count'),
            'title'     => $query->orderBy('title'),
            default     => $query->latest(),
        };

        $books      = $query->paginate(20)->withQueryString();
        $categories = Category::withCount(['books' => fn($q) => $q->where('status','active')])->orderBy('name')->get();

        // Distinct grade levels that have books
        $gradeLevels = Book::where('status', 'active')
            ->whereNotNull('grade_level')
            ->where('grade_level', '!=', '')
            ->distinct()->orderBy('grade_level')
            ->pluck('grade_level');

        // Stats
        $stats = [
            'total'      => Book::where('status', 'active')->count(),
            'downloads'  => (int) Book::where('status', 'active')->sum('download_count'),
            'categories' => Category::count(),
        ];

        // Type counts
        $typeCounts = Book::where('status', 'active')
            ->selectRaw('file_type, count(*) as cnt')
            ->groupBy('file_type')
            ->pluck('cnt', 'file_type');

        $featuredBooks = collect(); // kept for compatibility

        return view('student.library.index', compact(
            'books', 'categories', 'gradeLevels', 'stats', 'typeCounts', 'featuredBooks'
        ));
    }

    public function show(Book $book)
    {
        abort_if($book->status !== 'active', 404);
        $book->increment('view_count');
        return view('student.library.show', compact('book'));
    }

    public function download(Book $book)
    {
        abort_if($book->status !== 'active', 404);
        if ($book->external_url) {
            return redirect()->away($book->external_url);
        }
        if (!$book->file_path || !Storage::disk('public')->exists($book->file_path)) {
            return back()->with('error', 'File not available.');
        }
        $book->increment('download_count');
        return Storage::disk('public')->download($book->file_path, Str_replace('/', '_', $book->title) . '.' . pathinfo($book->file_path, PATHINFO_EXTENSION));
    }
}
