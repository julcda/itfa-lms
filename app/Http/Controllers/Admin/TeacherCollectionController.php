<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherCollection;
use App\Models\TeacherMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherCollectionController extends Controller
{
    // ── Index: all root collections ───────────────────────────────

    public function index(Request $request)
    {
        $userId = auth()->id();

        $collections = TeacherCollection::with(['children.materials', 'materials', 'creator'])
            ->roots()
            ->visibleTo($userId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Ungrouped materials (not in any collection)
        $ungroupedCount = TeacherMaterial::whereDoesntHave('collections')->count();

        $stats = [
            'collections'  => TeacherCollection::roots()->count(),
            'sub_collections' => TeacherCollection::whereNotNull('parent_id')->count(),
            'total_materials' => TeacherMaterial::count(),
            'ungrouped'    => $ungroupedCount,
        ];

        return view('admin.teacher-collections.index', compact('collections', 'stats', 'ungroupedCount'));
    }

    // ── Create form ───────────────────────────────────────────────

    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parent = $parentId ? TeacherCollection::find($parentId) : null;

        // Only root-level collections can be parents (max 2 levels)
        $parents = TeacherCollection::roots()
            ->visibleTo(auth()->id())
            ->orderBy('name')
            ->get();

        return view('admin.teacher-collections.create', compact('parent', 'parents'));
    }

    // ── Store ─────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:120',
            'name_ar'     => 'nullable|string|max:120',
            'description' => 'nullable|string|max:500',
            'cover_color' => 'nullable|string|max:20',
            'parent_id'   => 'nullable|exists:teacher_collections,id',
            'is_private'  => 'nullable|boolean',
            'icon'        => 'nullable|string|max:10',
        ]);

        $data['created_by'] = auth()->id();
        $data['is_private'] = $request->boolean('is_private');

        // Default sort order = end of current siblings
        $maxOrder = TeacherCollection::where('parent_id', $data['parent_id'] ?? null)->max('sort_order') ?? 0;
        $data['sort_order'] = $maxOrder + 1;

        $collection = TeacherCollection::create($data);

        return redirect()->route('admin.teacher-collections.show', $collection)
            ->with('success', __('messages.collection_created'));
    }

    // ── Show (detail page) ────────────────────────────────────────

    public function show(Request $request, TeacherCollection $teacherCollection)
    {
        $teacherCollection->load(['parent', 'children.materials', 'creator']);

        // Materials in this collection with search/filter
        $matQuery = $teacherCollection->materials()->with('category');

        if ($request->filled('search')) {
            $s = $request->search;
            $matQuery->where(fn($q) => $q->where('title', 'like', "%$s%")
                ->orWhere('subject', 'like', "%$s%"));
        }
        if ($request->filled('material_type')) {
            $matQuery->where('material_type', $request->material_type);
        }
        if ($request->filled('status')) {
            $matQuery->where('status', $request->status);
        }

        $materials = $matQuery->get();

        // Available materials not yet in this collection (for the Add modal)
        $availableMaterials = TeacherMaterial::whereNotIn('id', $teacherCollection->materials->pluck('id'))
            ->where('status', 'active')
            ->orderBy('title')
            ->get();

        // Breadcrumb
        $breadcrumb = collect();
        $current = $teacherCollection;
        while ($current->parent) {
            $breadcrumb->prepend($current->parent);
            $current = $current->parent;
        }

        return view('admin.teacher-collections.show', compact(
            'teacherCollection', 'materials', 'availableMaterials', 'breadcrumb'
        ));
    }

    // ── Edit form ─────────────────────────────────────────────────

    public function edit(TeacherCollection $teacherCollection)
    {
        $parents = TeacherCollection::roots()
            ->where('id', '!=', $teacherCollection->id)
            ->visibleTo(auth()->id())
            ->orderBy('name')
            ->get();

        return view('admin.teacher-collections.edit', compact('teacherCollection', 'parents'));
    }

    // ── Update ────────────────────────────────────────────────────

    public function update(Request $request, TeacherCollection $teacherCollection)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:120',
            'name_ar'     => 'nullable|string|max:120',
            'description' => 'nullable|string|max:500',
            'cover_color' => 'nullable|string|max:20',
            'parent_id'   => 'nullable|exists:teacher_collections,id',
            'is_private'  => 'nullable|boolean',
            'icon'        => 'nullable|string|max:10',
        ]);

        // Prevent circular reference
        if (!empty($data['parent_id']) && $data['parent_id'] == $teacherCollection->id) {
            $data['parent_id'] = null;
        }

        $data['is_private'] = $request->boolean('is_private');

        $teacherCollection->update($data);

        return redirect()->route('admin.teacher-collections.show', $teacherCollection)
            ->with('success', __('messages.collection_updated'));
    }

    // ── Destroy ───────────────────────────────────────────────────

    public function destroy(TeacherCollection $teacherCollection)
    {
        // Detach all materials first (pivot auto-cleans via cascade, but be explicit)
        $teacherCollection->materials()->detach();
        // Also destroy children
        foreach ($teacherCollection->children as $child) {
            $child->materials()->detach();
            $child->delete();
        }
        $teacherCollection->delete();

        return redirect()->route('admin.teacher-collections.index')
            ->with('success', __('messages.collection_deleted'));
    }

    // ── Add materials to collection (AJAX / form) ─────────────────

    public function addMaterials(Request $request, TeacherCollection $teacherCollection)
    {
        $request->validate([
            'material_ids'   => 'required|array',
            'material_ids.*' => 'exists:teacher_materials,id',
        ]);

        // Determine current max sort_order in this collection
        $maxOrder = DB::table('teacher_collection_material')
            ->where('teacher_collection_id', $teacherCollection->id)
            ->max('sort_order') ?? 0;

        $syncData = [];
        foreach ($request->material_ids as $i => $id) {
            $syncData[$id] = ['sort_order' => $maxOrder + $i + 1];
        }

        $teacherCollection->materials()->syncWithoutDetaching($syncData);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'count' => count($syncData)]);
        }

        return back()->with('success', count($syncData) . ' material(s) added to collection.');
    }

    // ── Remove material from collection ───────────────────────────

    public function removeMaterial(Request $request, TeacherCollection $teacherCollection, TeacherMaterial $teacherMaterial)
    {
        $teacherCollection->materials()->detach($teacherMaterial->id);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', __('messages.material_removed_from_collection'));
    }

    // ── Reorder materials within collection ───────────────────────

    public function reorderMaterials(Request $request, TeacherCollection $teacherCollection)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:teacher_materials,id',
        ]);

        foreach ($request->order as $position => $materialId) {
            DB::table('teacher_collection_material')
                ->where('teacher_collection_id', $teacherCollection->id)
                ->where('teacher_material_id', $materialId)
                ->update(['sort_order' => $position]);
        }

        return response()->json(['success' => true]);
    }

    // ── Reorder collections ───────────────────────────────────────

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:teacher_collections,id',
        ]);

        foreach ($request->order as $position => $collectionId) {
            TeacherCollection::where('id', $collectionId)->update(['sort_order' => $position]);
        }

        return response()->json(['success' => true]);
    }

    // ── Move material to another collection ───────────────────────

    public function moveMaterial(Request $request, TeacherCollection $teacherCollection, TeacherMaterial $teacherMaterial)
    {
        $request->validate([
            'target_collection_id' => 'required|exists:teacher_collections,id',
        ]);

        $target = TeacherCollection::findOrFail($request->target_collection_id);

        // Remove from current, add to target
        $teacherCollection->materials()->detach($teacherMaterial->id);
        $maxOrder = DB::table('teacher_collection_material')
            ->where('teacher_collection_id', $target->id)
            ->max('sort_order') ?? 0;
        $target->materials()->syncWithoutDetaching([$teacherMaterial->id => ['sort_order' => $maxOrder + 1]]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', __('messages.material_moved'));
    }
}
