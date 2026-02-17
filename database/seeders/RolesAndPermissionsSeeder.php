<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. تعريف قائمة الصلاحيات الأساسية للنظام
        $permissions = [
            // الفروع
            'view_branches', 'create_branch', 'update_branch', 'delete_branch',
            // الخدمات
            'view_services', 'create_service', 'update_service', 'delete_service',
            // الحجوزات
            'view_appointments', 'create_appointment', 'update_appointment', 'delete_appointment',
            // المستخدمين (الموظفين والعملاء)
            'view_staffs', 'create_staff', 'update_staff', 'delete_staff',
            'view_customers', 'create_customer', 'update_customer', 'delete_customer',
            // ساعات العمل والإغلاقات
            'manage_schedules',
            // الأدوار (تتم إدارتها بواسطة Owner و Branch Manager فقط كما حددنا في RolePolicy)
            'manage_roles',
        ];

        // 3. إنشاء الصلاحيات في قاعدة البيانات
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // المالك (Owner): له كل الصلاحيات (هذا للتوثيق، برمجياً لقد عطيناه الحصانة في AppServiceProvider)
        $roleOwner = Role::findOrCreate('Owner');
        $roleOwner->givePermissionTo(Permission::all());

        // مدير الفرع (Branch Manager): له صلاحيات الإدارة ولكن لا يمكنه حذف الفروع مثلاً
        $roleManager = Role::findOrCreate('Branch Manager');
        $roleManager->givePermissionTo([
            'view_branches', // يرى فرعه فقط (سنقوم بتقييدها برمجياً لاحقاً)
            'view_services', 'create_service', 'update_service',
            'view_staffs', 'create_staff', 'update_staff',
            'view_appointments', 'create_appointment', 'update_appointment',
            'manage_schedules',
            'manage_roles', // لكي يستطيع إضافة أدوار أخرى
        ]);

        // الموظف (Staff): صلاحيات محدودة جداً
        $roleStaff = Role::findOrCreate('Staff');
        $roleStaff->givePermissionTo([
            'view_appointments', 'create_appointment', 'update_appointment',
        ]);
        // العميل (Customer): لا يدخل للوحة التحكم أصلاً
        Role::findOrCreate('Customer');

    }
}
