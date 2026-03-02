<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Users
            'view users', 'create users', 'edit users', 'delete users',
            // Courses
            'view courses', 'create courses', 'edit courses', 'delete courses',
            // Lessons
            'view lessons', 'create lessons', 'edit lessons', 'delete lessons',
            // E-Library
            'view books', 'create books', 'edit books', 'delete books',
            // Quizzes
            'view quizzes', 'create quizzes', 'edit quizzes', 'delete quizzes',
            'attempt quizzes',
            // Attendance
            'view attendance', 'mark attendance',
            // Certificates
            'view certificates', 'issue certificates',
            // Categories
            'manage categories',
            // Enrollments
            'manage enrollments',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $student = Role::firstOrCreate(['name' => 'student']);

        $admin->syncPermissions(Permission::all());

        $teacher->syncPermissions([
            'view courses', 'create courses', 'edit courses',
            'view lessons', 'create lessons', 'edit lessons', 'delete lessons',
            'view books', 'create books',
            'view quizzes', 'create quizzes', 'edit quizzes', 'delete quizzes',
            'view attendance', 'mark attendance',
            'view certificates', 'issue certificates',
        ]);

        $student->syncPermissions([
            'view courses', 'view lessons', 'view books',
            'view quizzes', 'attempt quizzes',
            'view attendance', 'view certificates',
        ]);
    }
}
