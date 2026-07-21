<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cache reset করুন — migration-এর পরে জরুরি
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Permissions ──────────────────────────────────────────────────
        $permissions = [
            // Course management
            'course.viewAny',
            'course.view',
            'course.create',
            'course.update',
            'course.delete',

            // Module management
            'module.viewAny',
            'module.view',
            'module.create',
            'module.update',
            'module.delete',

            // Lesson management
            'lesson.viewAny',
            'lesson.view',
            'lesson.create',
            'lesson.update',
            'lesson.delete',

            // Enrollment
            'enrollment.viewAny',
            'enrollment.view',
            'enrollment.create',
            'enrollment.delete',

            // User management
            'user.viewAny',
            'user.view',
            'user.create',
            'user.update',
            'user.delete',

            // Reports
            'report.viewAny',
            'report.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── Roles ────────────────────────────────────────────────────────
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $instructor = Role::firstOrCreate(['name' => 'instructor']);
        $student    = Role::firstOrCreate(['name' => 'student']);

        // Admin → সব permission পাবে
        $admin->syncPermissions(Permission::all());

        // Instructor → নিজের course/module/lesson manage করতে পারবে
        $instructor->syncPermissions([
            'course.viewAny',
            'course.view',
            'course.create',
            'course.update',

            'module.viewAny',
            'module.view',
            'module.create',
            'module.update',
            'module.delete',

            'lesson.viewAny',
            'lesson.view',
            'lesson.create',
            'lesson.update',
            'lesson.delete',

            'enrollment.viewAny',
            'enrollment.view',
        ]);

        // Student → শুধু course দেখা ও enroll করতে পারবে
        $student->syncPermissions([
            'course.viewAny',
            'course.view',
            'lesson.view',
            'enrollment.view',
            'enrollment.create',
        ]);
    }
}
