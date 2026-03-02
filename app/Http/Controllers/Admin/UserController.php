<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%");
            });
        }
        if ($request->filled('role')) {
            $query->role($request->role);
        }
        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'arabic_name' => 'nullable|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'phone'       => 'nullable|string|max:20',
            'gender'      => 'nullable|in:male,female,other',
            'locale'      => 'nullable|in:en,ar',
            'role'        => 'required|in:admin,teacher,student',
            // K-12 DepEd fields
            'lrn'         => 'nullable|string|size:12|unique:users,lrn',
            'grade_level' => 'nullable|string|max:20',
            'section'     => 'nullable|string|max:100',
            'strand'      => 'nullable|string|max:30',
            'school_year' => 'nullable|string|max:20',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;
        $user = User::create($data);
        $user->assignRole($request->role);
        return redirect()->route('admin.users.index')->with('success', __('messages.user_created'));
    }

    public function show(User $user)
    {
        $user->load(['roles', 'enrollments.course', 'certificates.course']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'arabic_name' => 'nullable|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'password'    => 'nullable|string|min:8|confirmed',
            'phone'       => 'nullable|string|max:20',
            'gender'      => 'nullable|in:male,female,other',
            'locale'      => 'nullable|in:en,ar',
            'role'        => 'required|in:admin,teacher,student',
            // K-12 DepEd fields
            'lrn'         => 'nullable|string|size:12|unique:users,lrn,' . $user->id,
            'grade_level' => 'nullable|string|max:20',
            'section'     => 'nullable|string|max:100',
            'strand'      => 'nullable|string|max:30',
            'school_year' => 'nullable|string|max:20',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $user->update($data);
        $user->syncRoles([$request->role]);
        return redirect()->route('admin.users.index')->with('success', __('messages.user_updated'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', __('messages.cannot_delete_self'));
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', __('messages.user_deleted'));
    }
}
