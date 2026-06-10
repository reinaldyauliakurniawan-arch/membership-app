<?php

namespace App\Filament\Resources\CoachingSessions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CoachingSessionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('coach.name')->label('Coach'),
                        TextEntry::make('member.name')->label('Member'),
                        TextEntry::make('session_date')->label('Session Date')->date(),
                        TextEntry::make('duration_minutes')->label('Duration (minutes)'),
                        TextEntry::make('status')->label('Status')->badge(),
                        TextEntry::make('notes')->label('Notes')->placeholder('—')->columnSpanFull(),
                    ]),
            ]);
    }
}
