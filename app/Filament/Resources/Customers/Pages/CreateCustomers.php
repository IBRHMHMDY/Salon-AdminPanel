<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomersResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCustomers extends CreateRecord
{
    protected static string $resource = CustomersResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['salon_id'] = Auth::user()->salon_id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole('Customer');
    }
}
