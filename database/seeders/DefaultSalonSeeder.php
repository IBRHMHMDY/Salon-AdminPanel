<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultSalonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Salon
        $salon = Salon::create([
            'name' => 'Ahgzly Default Salon',
            'is_active' => true,
        ]);

        // 2. Create Main Branch
        $branch = Branch::create([
            'salon_id' => $salon->id,
            'name' => 'Main Branch',
            'address' => 'Cairo, Egypt',
            'is_main' => true,
            'is_active' => true,
        ]);

        // 3. Create Admin User (Owner)
        $owner = User::create([
            'salon_id' => $salon->id,
            'branch_id' => $branch->id,
            'name' => 'Ibrahim Admin',
            'email' => 'admin@ahgzly.com',
            'password' => Hash::make('password'),
            'phone' => '01000000000',
        ]);

        $owner->assignRole('Owner');
    }
}
