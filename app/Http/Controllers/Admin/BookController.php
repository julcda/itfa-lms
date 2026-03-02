<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['category', 'uploader']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('title_ar', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        $books = $query->latest()->paginate(15)->withQueryString();
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'author'         => 'nullable|string|max:255',
            'author_ar'      => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_ar' => 'nullable|string',
            'category_id'    => 'nullable|exists:categories,id',
            'file_type'      => 'required|in:pdf,epub,doc,video,audio,other,external',
            'external_url'   => 'nullable|url',
            'language'       => 'required|in:arabic,english,bilingual,filipino',
            'status'         => 'required|in:active,inactive',
            'cover_image'    => 'nullable|image|max:2048',
            'file_path'      => 'nullable|file|max:51200',
            // K-12 DepEd fields
            'grade_level'    => 'nullable|string|max:20',
            'learning_area'  => 'nullable|string|max:50',
            'deped_code'     => 'nullable|string|max:100',
            'edition'        => 'nullable|string|max:50',
        ]);
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('books/files', 'public');
        }
        $data['uploaded_by'] = auth()->id();
        Book::create($data);
        return redirect()->route('admin.books.index')->with('success', __('messages.book_created'));
    }

    public function show(Book $book)
    {
        $book->load(['category', 'uploader']);
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'author'         => 'nullable|string|max:255',
            'author_ar'      => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_ar' => 'nullable|string',
            'category_id'    => 'nullable|exists:categories,id',
            'file_type'      => 'required|in:pdf,epub,doc,video,audio,other,external',
            'external_url'   => 'nullable|url',
            'language'       => 'required|in:arabic,english,bilingual,filipino',
            'status'         => 'required|in:active,inactive',
            'cover_image'    => 'nullable|image|max:2048',
            'file_path'      => 'nullable|file|max:51200',
            // K-12 DepEd fields
            'grade_level'    => 'nullable|string|max:20',
            'learning_area'  => 'nullable|string|max:50',
            'deped_code'     => 'nullable|string|max:100',
            'edition'        => 'nullable|string|max:50',
        ]);
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }
        if ($request->hasFile('file_path')) {
            if ($book->file_path) Storage::disk('public')->delete($book->file_path);
            $data['file_path'] = $request->file('file_path')->store('books/files', 'public');
        }
        $book->update($data);
        return redirect()->route('admin.books.index')->with('success', __('messages.book_updated'));
    }

    public function destroy(Book $book)
    {
        if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);
        if ($book->file_path) Storage::disk('public')->delete($book->file_path);
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', __('messages.book_deleted'));
    }
}
