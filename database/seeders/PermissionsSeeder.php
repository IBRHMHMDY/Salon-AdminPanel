<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إعادة تعيين ذاكرة التخزين المؤقت للصلاحيات
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. تعريف قائمة الصلاحيات الدقيقة والموحدة
        $permissions = [
            // الإحصائيات ولوحة التحكم
            'view_dashboard',
            'view_revenue_stats',
            'view_personal_stats',

            // إدارة الفروع
            'view_any_branch',
            'view_branch',
            'create_branch',
            'update_branch',
            'delete_branch',

            // إدارة الموظفين
            'view_any_staff',
            'view_staff',
            'create_staff',
            'update_staff',
            'delete_staff',

            // إدارة العملاء
            'view_any_customer',
            'view_customer',
            'create_customer',
            'update_customer',
            'delete_customer',

            // إدارة الخدمات
            'view_any_service',
            'view_service',
            'create_service',
            'update_service',
            'delete_service',

            // إدارة المواعيد (Appointments)
            'view_any_appointment', // رؤية كل المواعيد (للمالك والمدير)
            'view_own_appointment', // رؤية مواعيده الخاصة فقط (للموظف)
            'view_appointment',     // لعرض تفاصيل موعد معين
            'create_appointment',
            'update_appointment',
            'delete_appointment',

            // إعدادات النظام (مواعيد العمل والأجازات والأدوار)
            'manage_working_hours',
            'manage_closures',
            'manage_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
