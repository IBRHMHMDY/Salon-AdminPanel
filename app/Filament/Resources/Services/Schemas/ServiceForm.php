<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('duration_minutes')
                            ->label('Duration (Min)')
                            ->numeric()
                            ->required()
                            ->suffix('min')
                            ->default(30),
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Toggle::make('is_active')
                            ->default(true),

                        // ربط الموظفين بالخدمة (The Skill Matrix)
                        Select::make('providers')
                            ->relationship('providers', 'name', function (Builder $query) {
                                return $query
                                    // 1. عزل البيانات: الموظفون التابعون لنفس صالون المستخدم الحالي فقط
                                    ->where('salon_id', auth()->user()->salon_id)

                                    // 2. الفلترة حسب الدور: إظهار من يحمل دور Staff حصراً
                                    ->whereHas('roles', function ($q) {
                                        $q->where('name', 'employee');
                                    });

                            })
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Assign Staff to this Service'),
                    ])->columns(2),
            ]);
    }
}
