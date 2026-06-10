<?php

namespace App\Filament\Resources\Divisions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DivisionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name')->label('Name'),
                        TextEntry::make('order')->label('Display Order'),
                        TextEntry::make('min_rating')->label('Min Rating'),
                        TextEntry::make('max_rating')
                            ->label('Max Rating')
                            ->placeholder('No ceiling'),
                        TextEntry::make('description')
                            ->label('Description')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
