<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds DepEd K-12 curriculum categories (Learning Areas by School Level).
 * Philippine Department of Education – K to 12 Basic Education Program.
 */
class K12Seeder extends Seeder
{
    public function run(): void
    {
        // ─── Parent Level Categories ──────────────────────────────────────────
        $kinder = Category::firstOrCreate(
            ['slug' => 'kindergarten'],
            [
                'name'        => 'Kindergarten',
                'name_ar'     => 'Kindergarten',
                'description' => 'DepEd Kindergarten Program (Age 5)',
                'type'        => 'both',
                'icon'        => 'fas fa-child',
                'order'       => 1,
                'is_active'   => true,
            ]
        );

        $elementary = Category::firstOrCreate(
            ['slug' => 'elementary'],
            [
                'name'        => 'Elementary (Grades 1–6)',
                'name_ar'     => 'Elementary (Grades 1–6)',
                'description' => 'DepEd Primary Education – Grades 1 to 6',
                'type'        => 'both',
                'icon'        => 'fas fa-book-open',
                'order'       => 2,
                'is_active'   => true,
            ]
        );

        $jhs = Category::firstOrCreate(
            ['slug' => 'junior-high-school'],
            [
                'name'        => 'Junior High School (Grades 7–10)',
                'name_ar'     => 'Junior High School (Grades 7–10)',
                'description' => 'DepEd Junior High School – Grades 7 to 10',
                'type'        => 'both',
                'icon'        => 'fas fa-school',
                'order'       => 3,
                'is_active'   => true,
            ]
        );

        $shs = Category::firstOrCreate(
            ['slug' => 'senior-high-school'],
            [
                'name'        => 'Senior High School (Grades 11–12)',
                'name_ar'     => 'Senior High School (Grades 11–12)',
                'description' => 'DepEd Senior High School – Grades 11 to 12',
                'type'        => 'both',
                'icon'        => 'fas fa-graduation-cap',
                'order'       => 4,
                'is_active'   => true,
            ]
        );

        // ─── Elementary Learning Areas ────────────────────────────────────────
        $elementaryAreas = [
            ['name' => 'Mother Tongue',              'slug' => 'el-mother-tongue',    'icon' => 'fas fa-comment-dots',   'order' => 1],
            ['name' => 'Filipino',                   'slug' => 'el-filipino',          'icon' => 'fas fa-flag',           'order' => 2],
            ['name' => 'English',                    'slug' => 'el-english',           'icon' => 'fas fa-spell-check',    'order' => 3],
            ['name' => 'Mathematics',                'slug' => 'el-mathematics',       'icon' => 'fas fa-calculator',     'order' => 4],
            ['name' => 'Science',                    'slug' => 'el-science',           'icon' => 'fas fa-flask',          'order' => 5],
            ['name' => 'Araling Panlipunan (AP)',    'slug' => 'el-ap',                'icon' => 'fas fa-globe-asia',     'order' => 6],
            ['name' => 'Edukasyon sa Pagpapahalaga (EsP)', 'slug' => 'el-esp',         'icon' => 'fas fa-heart',          'order' => 7],
            ['name' => 'MAPEH',                      'slug' => 'el-mapeh',             'icon' => 'fas fa-music',          'order' => 8],
            ['name' => 'EPP / TLE',                  'slug' => 'el-epp-tle',           'icon' => 'fas fa-tools',          'order' => 9],
        ];

        foreach ($elementaryAreas as $area) {
            Category::firstOrCreate(
                ['slug' => $area['slug']],
                [
                    'name'      => $area['name'],
                    'name_ar'   => $area['name'],
                    'type'      => 'both',
                    'icon'      => $area['icon'],
                    'order'     => $area['order'],
                    'parent_id' => $elementary->id,
                    'is_active' => true,
                ]
            );
        }

        // ─── Junior High School Learning Areas ───────────────────────────────
        $jhsAreas = [
            ['name' => 'Filipino',                   'slug' => 'jhs-filipino',         'icon' => 'fas fa-flag',           'order' => 1],
            ['name' => 'English',                    'slug' => 'jhs-english',          'icon' => 'fas fa-spell-check',    'order' => 2],
            ['name' => 'Mathematics',                'slug' => 'jhs-mathematics',      'icon' => 'fas fa-calculator',     'order' => 3],
            ['name' => 'Science',                    'slug' => 'jhs-science',          'icon' => 'fas fa-flask',          'order' => 4],
            ['name' => 'Araling Panlipunan (AP)',    'slug' => 'jhs-ap',               'icon' => 'fas fa-globe-asia',     'order' => 5],
            ['name' => 'Edukasyon sa Pagpapahalaga (EsP)', 'slug' => 'jhs-esp',        'icon' => 'fas fa-heart',          'order' => 6],
            ['name' => 'MAPEH',                      'slug' => 'jhs-mapeh',            'icon' => 'fas fa-music',          'order' => 7],
            ['name' => 'TLE / Computer Studies',     'slug' => 'jhs-tle',              'icon' => 'fas fa-laptop',         'order' => 8],
        ];

        foreach ($jhsAreas as $area) {
            Category::firstOrCreate(
                ['slug' => $area['slug']],
                [
                    'name'      => $area['name'],
                    'name_ar'   => $area['name'],
                    'type'      => 'both',
                    'icon'      => $area['icon'],
                    'order'     => $area['order'],
                    'parent_id' => $jhs->id,
                    'is_active' => true,
                ]
            );
        }

        // ─── Senior High School Categories ───────────────────────────────────
        $shsCategories = [
            ['name' => 'SHS Core Subjects',              'slug' => 'shs-core',         'icon' => 'fas fa-book',            'order' => 1],
            ['name' => 'Academic Track – STEM',          'slug' => 'shs-stem',         'icon' => 'fas fa-atom',            'order' => 2],
            ['name' => 'Academic Track – ABM',           'slug' => 'shs-abm',          'icon' => 'fas fa-chart-line',      'order' => 3],
            ['name' => 'Academic Track – HUMSS',         'slug' => 'shs-humss',        'icon' => 'fas fa-users',           'order' => 4],
            ['name' => 'Academic Track – GAS',           'slug' => 'shs-gas',          'icon' => 'fas fa-layer-group',     'order' => 5],
            ['name' => 'TVL Track – Home Economics',     'slug' => 'shs-tvl-he',       'icon' => 'fas fa-utensils',        'order' => 6],
            ['name' => 'TVL Track – ICT',                'slug' => 'shs-tvl-ict',      'icon' => 'fas fa-network-wired',   'order' => 7],
            ['name' => 'TVL Track – Industrial Arts',    'slug' => 'shs-tvl-ia',       'icon' => 'fas fa-hard-hat',        'order' => 8],
            ['name' => 'TVL Track – Agri-Fishery Arts',  'slug' => 'shs-tvl-af',       'icon' => 'fas fa-seedling',        'order' => 9],
            ['name' => 'Sports Track',                   'slug' => 'shs-sports',       'icon' => 'fas fa-running',         'order' => 10],
            ['name' => 'Arts & Design Track',            'slug' => 'shs-arts-design',  'icon' => 'fas fa-palette',         'order' => 11],
        ];

        foreach ($shsCategories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name'      => $cat['name'],
                    'name_ar'   => $cat['name'],
                    'type'      => 'both',
                    'icon'      => $cat['icon'],
                    'order'     => $cat['order'],
                    'parent_id' => $shs->id,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('K-12 DepEd categories seeded successfully.');
    }
}
