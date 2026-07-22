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

            // Quiz & Assignment management
            'quiz.viewAny',
            'quiz.view',
            'quiz.create',
            'quiz.update',
            'quiz.delete',
            'assignment.viewAny',
            'assignment.view',
            'assignment.create',
            'assignment.update',
            'assignment.delete',
            'assignment.grade',

            // Coupons & Subscription plans
            'coupon.viewAny',
            'coupon.create',
            'coupon.update',
            'coupon.delete',
            'subscriptionPlan.viewAny',
            'subscriptionPlan.create',
            'subscriptionPlan.update',
            'subscriptionPlan.delete',

            // Devices
            'device.viewAny',
            'device.delete',

            // Certificates & Reviews
            'certificate.viewAny',
            'review.viewAny',
            'review.moderate',

            // Support tickets
            'ticket.viewAny',
            'ticket.view',
            'ticket.reply',
            'ticket.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── Roles ────────────────────────────────────────────────────────
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $instructor = Role::firstOrCreate(['name' => 'instructor']);
        $student    = Role::firstOrCreate(['name' => 'student']);
        $support    = Role::firstOrCreate(['name' => 'support']);

        // Admin → সব permission পাবে
        $admin->syncPermissions(Permission::all());

        // Support → ticket ও enrollment/report দেখতে পারবে, রিফান্ড/পেমেন্ট ইস্যু হ্যান্ডেল করার জন্য
        $support->syncPermissions([
            'ticket.viewAny',
            'ticket.view',
            'ticket.reply',
            'ticket.update',
            'enrollment.viewAny',
            'enrollment.view',
            'report.viewAny',
            'report.view',
            'review.viewAny',
            'review.moderate',
        ]);

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

            'quiz.viewAny',
            'quiz.view',
            'quiz.create',
            'quiz.update',
            'quiz.delete',
            'assignment.viewAny',
            'assignment.view',
            'assignment.create',
            'assignment.update',
            'assignment.delete',
            'assignment.grade',

            'certificate.viewAny',
            'review.viewAny',
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
