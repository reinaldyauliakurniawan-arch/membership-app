<?php

namespace App\Filament\Resources\CoachingSessions\Schemas;

use App\Models\Invoice;
use App\Models\Member;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CoachingSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Select::make('coach_id')
                            ->label('Coach')
                            ->options(fn () => Member::where('is_coach', true)->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Select::make('member_id')
                            ->label('Member')
                            ->options(fn () => Member::pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        DatePicker::make('session_date')
                            ->label('Session Date')
                            ->required(),
                        TextInput::make('duration_minutes')
                            ->label('Duration (minutes)')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('scheduled')
                            ->required(),
                        Select::make('invoice_id')
                            ->label('Invoice')
                            ->options(fn () => Invoice::query()->whereNotNull('number')->pluck('number', 'id'))
                            ->nullable()
                            ->placeholder('Link to invoice (optional)'),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
