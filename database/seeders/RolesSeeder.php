<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Owner
        $ownerRole = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'web']);
        $ownerRole->syncPermissions(Permission::all()); // إعطاء كل الصلاحيات

        // 2.Branch Manager
        $branchManagerRole = Role::firstOrCreate(['name' => 'Branch Manager', 'guard_name' => 'web']);
        $branchManagerRole->syncPermissions([
            'view_dashboard',
            'view_any_branch', 'view_branch',
            'view_any_staff', 'view_staff', 'create_staff', 'update_staff',
            'view_any_customer', 'view_customer', 'create_customer', 'update_customer',
            'view_any_service', 'view_service',
            'view_any_appointment', 'view_appointment', 'create_appointment', 'update_appointment', 'delete_appointment',
            'manage_working_hours', 'manage_closures',
        ]);

        // 3. Staff Members
        $staffRole = Role::firstOrCreate(['name' => 'Staff Members', 'guard_name' => 'web']);
        $staffRole->syncPermissions([
            'view_dashboard',
            'view_personal_stats',
            'view_own_appointment', // لرؤية مواعيده في القائمة والجداول
            'view_appointment',     // لفتح صفحة تفاصيل الموعد الخاص به
            'update_appointment',   // لتحديث حالة الموعد
        ]);

        $customerRole = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
    }
}
