<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TeacherMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = TeacherMaterial::with(['category', 'uploader']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('title', 'like', "%$s%")
                ->orWhere('title_ar', 'like', "%$s%")
                ->orWhere('subject', 'like', "%$s%"));
        }
        if ($request->filled('material_type')) $query->where('material_type', $request->material_type);
        if ($request->filled('subject'))       $query->where('subject', $request->subject);
        if ($request->filled('grade_level'))   $query->where('grade_level', $request->grade_level);
        if ($request->filled('status'))        $query->where('status', $request->status);
        if ($request->filled('category_id'))   $query->where('category_id', $request->category_id);

        $materials   = $query->latest()->paginate(20)->withQueryString();
        $categories  = Category::orderBy('name')->get();
        $subjects    = TeacherMaterial::whereNotNull('subject')->distinct()->orderBy('subject')->pluck('subject');
        $gradeLevels = TeacherMaterial::whereNotNull('grade_level')->distinct()->orderBy('grade_level')->pluck('grade_level');

        $stats = [
            'total'     => TeacherMaterial::count(),
            'active'    => TeacherMaterial::where('status', 'active')->count(),
            'draft'     => TeacherMaterial::where('status', 'draft')->count(),
            'downloads' => (int) TeacherMaterial::sum('download_count'),
        ];

        return view('admin.teacher-materials.index', compact(
            'materials', 'categories', 'subjects', 'gradeLevels', 'stats'
        ));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.teacher-materials.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_ar' => 'nullable|string',
            'subject'        => 'nullable|string|max:100',
            'grade_level'    => 'nullable|string|max:50',
            'category_id'    => 'nullable|exists:categories,id',
            'material_type'  => 'required|in:' . implode(',', TeacherMaterial::allTypes()),
            'language'       => 'required|in:english,arabic,bilingual,filipino',
            'status'         => 'required|in:active,draft',
            'external_url'   => 'nullable|url',
            'source'         => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'tags'           => 'nullable|string',
            'cover_image'    => 'nullable|image|max:2048',
            'file_path'      => 'nullable|file|max:102400', // 100 MB
        ]);

        // Parse tags
        if (!empty($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('teacher-materials/covers', 'public');
        }
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('teacher-materials/files', 'public');
        }

        $data['uploaded_by'] = auth()->id();
        TeacherMaterial::create($data);

        return redirect()->route('admin.teacher-materials.index')
            ->with('success', __('messages.teacher_material_created'));
    }

    public function show(TeacherMaterial $teacherMaterial)
    {
        $teacherMaterial->load(['category', 'uploader']);
        $teacherMaterial->increment('view_count');
        return view('admin.teacher-materials.show', compact('teacherMaterial'));
    }

    public function edit(TeacherMaterial $teacherMaterial)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.teacher-materials.edit', compact('teacherMaterial', 'categories'));
    }

    public function update(Request $request, TeacherMaterial $teacherMaterial)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_ar' => 'nullable|string',
            'subject'        => 'nullable|string|max:100',
            'grade_level'    => 'nullable|string|max:50',
            'category_id'    => 'nullable|exists:categories,id',
            'material_type'  => 'required|in:' . implode(',', TeacherMaterial::allTypes()),
            'language'       => 'required|in:english,arabic,bilingual,filipino',
            'status'         => 'required|in:active,draft',
            'external_url'   => 'nullable|url',
            'source'         => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'tags'           => 'nullable|string',
            'cover_image'    => 'nullable|image|max:2048',
            'file_path'      => 'nullable|file|max:102400',
        ]);

        if (!empty($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        if ($request->hasFile('cover_image')) {
            if ($teacherMaterial->cover_image) Storage::disk('public')->delete($teacherMaterial->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('teacher-materials/covers', 'public');
        }
        if ($request->hasFile('file_path')) {
            if ($teacherMaterial->file_path) Storage::disk('public')->delete($teacherMaterial->file_path);
            $data['file_path'] = $request->file('file_path')->store('teacher-materials/files', 'public');
        }

        $teacherMaterial->update($data);

        return redirect()->route('admin.teacher-materials.index')
            ->with('success', __('messages.teacher_material_updated'));
    }

    public function destroy(TeacherMaterial $teacherMaterial)
    {
        if ($teacherMaterial->cover_image) Storage::disk('public')->delete($teacherMaterial->cover_image);
        if ($teacherMaterial->file_path)   Storage::disk('public')->delete($teacherMaterial->file_path);
        $teacherMaterial->delete();

        return redirect()->route('admin.teacher-materials.index')
            ->with('success', __('messages.teacher_material_deleted'));
    }

    public function download(TeacherMaterial $teacherMaterial)
    {
        if ($teacherMaterial->external_url) {
            $teacherMaterial->increment('download_count');
            return redirect()->away($teacherMaterial->external_url);
        }
        if (!$teacherMaterial->file_path || !Storage::disk('public')->exists($teacherMaterial->file_path)) {
            return back()->with('error', 'File not available.');
        }
        $teacherMaterial->increment('download_count');
        return Storage::disk('public')->download($teacherMaterial->file_path, $teacherMaterial->title);
    }
}
