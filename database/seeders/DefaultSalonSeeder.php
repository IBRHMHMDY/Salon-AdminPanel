<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Salon;
use App\Models\User; // استخدم موديل User الأساسي للمالك
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultSalonSeeder extends Seeder
{
    public function run(): void
    {
        // Create Default Salon
        $salon = Salon::firstOrCreate(
            ['email' => 'default@ahgzly.com'],
            [
                'name' => 'Default Salon',
            ]
        );
        // Create Main Branch
        $mainBranch = Branch::firstOrCreate(
            ['name' => 'Default Main Branch'],
            [
                'address' => 'Egypt Cairo',
                'phone' => '01111111111',
                'salon_id' => $salon->id,
                'is_main' => true,
                'is_active' => true,

            ]
        );
        // 2. إنشاء حساب المالك (Owner)
        $ownerUser = User::firstOrCreate(
            ['email' => 'ibrahim@gmail.com'], // حسابك لدخول لوحة التحكم
            [
                'name' => 'Ibrahim',
                'phone' => '01111111111',
                'password' => Hash::make('ibrahim@0000'),
                'salon_id' => $salon->id,
                'branch_id' => $mainBranch->id,
            ]
        );

        // 3. إعطاء المالك دور "Owner"
        if (! $ownerUser->hasRole('Owner')) {
            $ownerUser->assignRole('Owner');
        }
    }
}
