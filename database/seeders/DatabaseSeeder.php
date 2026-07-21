<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ১. আগে roles ও permissions তৈরি করুন
        $this->call(RolePermissionSeeder::class);

        // ২. Admin user তৈরি করুন
        $admin = User::firstOrCreate(
            ['email' => 'admin@lms.test'],
            ['name' => 'Super Admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        // ৩. Instructor user তৈরি করুন
        $instructor = User::firstOrCreate(
            ['email' => 'instructor@lms.test'],
            ['name' => 'Demo Instructor', 'password' => bcrypt('password')]
        );
        $instructor->assignRole('instructor');

        // ৪. Student user তৈরি করুন
        $student = User::firstOrCreate(
            ['email' => 'student@lms.test'],
            ['name' => 'Demo Student', 'password' => bcrypt('password')]
        );
        $student->assignRole('student');
    }
}
