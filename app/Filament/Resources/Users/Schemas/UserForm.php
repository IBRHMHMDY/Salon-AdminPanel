<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Information')
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

                        // ✅ القيد: إخفاء دور Owner
                        Select::make('roles')
                            ->relationship(
                                'roles',
                                'name',
                                modifyQueryUsing: fn (Builder $query) => $query->whereNotIn('name', ['Owner', 'Customer'])
                            )
                            ->live()
                            ->preload()
                            ->searchable()
                            ->required(),
                        // ✅ القيد: إخفاء الفرع الرئيسي من القائمة
                        // بما أن الـ Owner فقط هو من يملك الفرع الرئيسي، ونحن هنا ننشئ موظفين، نمنع اختيار الفرع الرئيسي.
                        Select::make('branch_id')
                            ->relationship(
                                'branch',
                                'name',
                                modifyQueryUsing: function (Builder $query, Get $get) {
                                    $query->where('salon_id', Auth::user()->salon_id);

                                    // ⚡️ الحل هنا: إجبار القيمة لتكون مصفوفة دائماً لتجنب خطأ in_array
                                    $selectedRoles = (array) $get('roles');

                                    $managerRoleId = Role::where('name', 'Manager')->value('id');

                                    // التحقق بأمان
                                    if (in_array($managerRoleId, $selectedRoles)) {
                                        $query->where('is_main', false);
                                    }
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Assigned Branch')
                            ->helperText(function (Get $get) {
                                // ⚡️ وتطبيق نفس الحل هنا أيضاً
                                $selectedRoles = (array) $get('roles');
                                $managerRoleId = Role::where('name', 'Manager')->value('id');

                                if (in_array($managerRoleId, $selectedRoles)) {
                                    return 'Main Branch is hidden because it is managed directly by the Owner.';
                                }

                                return 'Select a branch for this employee. Main branch is allowed.';
                            }),

                    ])->columns(2),
            ]);
    }
}
