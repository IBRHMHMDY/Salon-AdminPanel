<?php

namespace App\Filament\Resources\Staffs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable()
                    ->placeholder('Main/All'),
                TextColumn::make('roles.name')->label('Role')->badge(),
                TextColumn::make('created_at')->dateTime(),
            ]);
    }
}
