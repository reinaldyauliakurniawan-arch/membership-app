<?php

namespace App\Filament\Resources\CoachingSessions;

use App\Filament\Resources\CoachingSessions\Pages\ListCoachingSessions;
use App\Filament\Resources\CoachingSessions\Schemas\CoachingSessionForm;
use App\Filament\Resources\CoachingSessions\Schemas\CoachingSessionInfolist;
use App\Filament\Resources\CoachingSessions\Tables\CoachingSessionTable;
use App\Models\CoachingSession;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CoachingSessionResource extends Resource
{
    protected static ?string $model = CoachingSession::class;

    protected static ?string $recordTitleAttribute = 'session_date';

    public static function getModelLabel(): string
    {
        return 'Coaching Session';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Coaching Sessions';
    }

    public static function getNavigationLabel(): string
    {
        return static::getPluralModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        return CoachingSessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoachingSessionTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CoachingSessionInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoachingSessions::route('/'),
        ];
    }
}
