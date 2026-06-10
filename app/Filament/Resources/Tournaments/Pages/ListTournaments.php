<?php

namespace App\Filament\Resources\Tournaments\Pages;

use App\Filament\Resources\Tournaments\TournamentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListTournaments extends ListRecords
{
    protected static string $resource = TournamentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Tournament')
                ->modalHeading('Add Tournament')
                ->modalSubmitActionLabel('Save')
                ->modalWidth(Width::Large)
                ->closeModalByClickingAway(false),
        ];
    }
}
