<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // ربط الخدمة بصالون المستخدم الحالي تلقائياً
        $data['salon_id'] = auth()->user()->salon_id;

        return $data;
    }
}
