<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Role Details')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->disabled(fn (?Role $record): bool => $record && in_array($record->name, ['Owner', 'Branch Manager', 'Staff', 'Customer'])),
                    // قمنا بتعطيل تغيير اسم الأدوار الأساسية حتى لا ينهار النظام

                    CheckboxList::make('permissions')
                        ->relationship(name: 'permissions', titleAttribute: 'name')
                        ->searchable()
                        ->bulkToggleable()
                        ->columns(2)
                        ->gridDirection('row'),
                ]),
        ]);
    }
}
