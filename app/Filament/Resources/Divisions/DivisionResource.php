<?php

namespace App\Filament\Resources\Divisions;

use App\Filament\Resources\Divisions\Pages\ListDivisions;
use App\Filament\Resources\Divisions\Schemas\DivisionForm;
use App\Filament\Resources\Divisions\Schemas\DivisionInfolist;
use App\Filament\Resources\Divisions\Tables\DivisionTable;
use App\Models\Division;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DivisionResource extends Resource
{
    protected static ?string $model = Division::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return 'Division';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Divisions';
    }

    public static function getNavigationLabel(): string
    {
        return static::getPluralModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        return DivisionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DivisionTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DivisionInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDivisions::route('/'),
        ];
    }
}
