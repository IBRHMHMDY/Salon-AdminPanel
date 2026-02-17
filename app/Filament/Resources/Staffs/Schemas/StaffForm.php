<?php

namespace App\Filament\Resources\Staffs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Staff Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->tel(),

                        // Password handling: Required on create, nullable on edit
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),

                        // ✅ القيد: إخفاء دور Owner, Customer
                        Select::make('roles')
                            ->relationship(
                                'roles', // تمرير مباشر
                                'name',
                                // ⚡️ تم تصحيح اسم المالك إلى Owner Salon ليتطابق مع الـ Seeder
                                fn (Builder $query) => $query->whereNotIn('name', ['Owner', 'Customer'])
                            )
                            ->multiple() // ⚡️ إضافة هامة جداً (لأن الموظف يمكن أن يكون له أكثر من دور)
                            ->live()
                            ->preload()
                            ->searchable()
                            ->required(),
                        // ✅ القيد: إخفاء الفرع الرئيسي من القائمة
                        // بما أن الـ Owner فقط هو من يملك الفرع الرئيسي، ونحن هنا ننشئ موظفين، نمنع اختيار الفرع الرئيسي.
                        Select::make('branch_id')
                            ->relationship(
                                'branch', // ⚡️ تمرير مباشر لاسم العلاقة
                                'name',   // ⚡️ تمرير مباشر للحقل المرئي (هذا يمنع ظهور الـ ID نهائياً)
                                function (Builder $query, Get $get) {
                                    $query->where('salon_id', Auth::user()->salon_id);

                                    $selectedRoles = (array) $get('roles');
                                    $managerRoleId = Role::where('name', 'Branch Manager')->value('id');

                                    // التحقق بأمان
                                    if (in_array($managerRoleId, $selectedRoles)) {
                                        $query->where('is_main', false);
                                    }

                                    return $query; // ⚡️ ضروري جداً إرجاع الاستعلام لكي لا ينكسر الحقل
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Assigned Branch')
                            ->helperText(function (Get $get) {
                                $selectedRoles = (array) $get('roles');
                                $managerRoleId = Role::where('name', 'Branch Manager')->value('id');

                                if (in_array($managerRoleId, $selectedRoles)) {
                                    return 'Main Branch is hidden because it is managed directly by the Owner.';
                                }

                                return 'Select a branch for this staff. Main branch is allowed.';
                            }),

                    ])->columns(2),
            ]);
    }
}
