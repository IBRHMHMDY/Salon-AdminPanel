<?php

namespace App\Filament\Resources\Staffs\Pages;

use App\Filament\Resources\Staffs\StaffResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // ⚡️ الحل الجذري: اعتراض البيانات وحقن salon_id قبل الحفظ
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // إسناد الموظف الجديد إلى نفس صالون المستخدم الحالي (المالك/المدير)
        $data['salon_id'] = Auth::user()->salon_id;

        return $data;
    }
}
