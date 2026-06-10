<?php

namespace App\Filament\Resources\Tournaments\Schemas;

use App\Models\Division;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TournamentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Tournament Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        DatePicker::make('date')
                            ->label('Date')
                            ->required(),
                        TextInput::make('location')
                            ->label('Location')
                            ->maxLength(255),
                        Select::make('format')
                            ->label('Format')
                            ->options([
                                'swiss' => 'Swiss',
                                'round_robin' => 'Round Robin',
                                'king_of_the_hill' => 'King of the Hill',
                            ])
                            ->required(),
                        Select::make('division_id')
                            ->label('Division')
                            ->options(fn () => Division::orderBy('order')->pluck('name', 'id'))
                            ->placeholder('All Divisions')
                            ->nullable(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'upcoming' => 'Upcoming',
                                'ongoing' => 'Ongoing',
                                'completed' => 'Completed',
                            ])
                            ->default('upcoming')
                            ->required(),
                        Toggle::make('isf_rated')
                            ->label('ISF Rated')
                            ->default(true),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
