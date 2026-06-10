<?php

namespace App\Filament\Resources\Divisions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DivisionForm
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
                            ->label('Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. A, B, C'),
                        TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        TextInput::make('min_rating')
                            ->label('Min Rating')
                            ->numeric()
                            ->required()
                            ->minValue(0),
                        TextInput::make('max_rating')
                            ->label('Max Rating')
                            ->numeric()
                            ->minValue(0)
                            ->hint('Leave empty for no ceiling'),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
