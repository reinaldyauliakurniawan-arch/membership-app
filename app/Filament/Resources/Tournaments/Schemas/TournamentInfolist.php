<?php

namespace App\Filament\Resources\Tournaments\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TournamentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name')->label('Tournament Name')->columnSpanFull(),
                        TextEntry::make('date')->label('Date')->date(),
                        TextEntry::make('location')->label('Location')->placeholder('—'),
                        TextEntry::make('format')->label('Format')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'swiss' => 'Swiss',
                                'round_robin' => 'Round Robin',
                                'king_of_the_hill' => 'King of the Hill',
                                default => $state,
                            }),
                        TextEntry::make('division.name')->label('Division')->placeholder('All Divisions'),
                        TextEntry::make('status')->label('Status')->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'upcoming' => 'info',
                                'ongoing' => 'warning',
                                'completed' => 'success',
                                default => 'gray',
                            }),
                        IconEntry::make('isf_rated')->label('ISF Rated')->boolean(),
                        TextEntry::make('notes')->label('Notes')->placeholder('—')->columnSpanFull(),
                    ]),
            ]);
    }
}
