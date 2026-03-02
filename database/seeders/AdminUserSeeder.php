<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@itfa.edu'],
            [
                'name'        => 'Administrator',
                'arabic_name' => 'المدير',
                'password'    => Hash::make('password'),
                'locale'      => 'ar',
                'is_active'   => true,
            ]
        );
        $admin->assignRole('admin');

        $teacher = User::firstOrCreate(
            ['email' => 'teacher@itfa.edu'],
            [
                'name'        => 'Teacher Demo',
                'arabic_name' => 'أستاذ تجريبي',
                'password'    => Hash::make('password'),
                'locale'      => 'ar',
                'is_active'   => true,
            ]
        );
        $teacher->assignRole('teacher');

        $student = User::firstOrCreate(
            ['email' => 'student@itfa.edu'],
            [
                'name'        => 'Student Demo',
                'arabic_name' => 'طالب تجريبي',
                'password'    => Hash::make('password'),
                'locale'      => 'ar',
                'is_active'   => true,
            ]
        );
        $student->assignRole('student');
    }
}
