<?php

namespace App\Filament\Resources\Tournaments;

use App\Filament\Resources\Tournaments\Pages\ListTournaments;
use App\Filament\Resources\Tournaments\Schemas\TournamentForm;
use App\Filament\Resources\Tournaments\Schemas\TournamentInfolist;
use App\Filament\Resources\Tournaments\Tables\TournamentTable;
use App\Models\Tournament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TournamentResource extends Resource
{
    protected static ?string $model = Tournament::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return 'Tournament';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tournaments';
    }

    public static function getNavigationLabel(): string
    {
        return static::getPluralModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        return TournamentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TournamentTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TournamentInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTournaments::route('/'),
        ];
    }
}
