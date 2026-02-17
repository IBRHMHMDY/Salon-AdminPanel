<?php

namespace App\Filament\Resources\Staffs;

use App\Filament\Resources\Staffs\Pages\CreateStaff;
use App\Filament\Resources\Staffs\Pages\EditStaff;
use App\Filament\Resources\Staffs\Pages\ListStaff;
use App\Filament\Resources\Staffs\Schemas\StaffForm;
use App\Filament\Resources\Staffs\Tables\StaffTable;
use App\Models\Staff;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    protected static ?string $navigationLabel = 'Staff Members';

    protected static ?string $pluralModelLabel = 'Staff Members';

    protected static ?string $recordTitleAttribute = 'Staff';

    public static function form(Schema $schema): Schema
    {
        return StaffForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaff::route('/'),
            'create' => CreateStaff::route('/create'),
            'edit' => EditStaff::route('/{record}/edit'),
        ];
    }
}
