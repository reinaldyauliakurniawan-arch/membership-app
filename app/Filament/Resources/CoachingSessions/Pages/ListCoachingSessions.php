<?php

namespace App\Filament\Resources\CoachingSessions\Pages;

use App\Filament\Resources\CoachingSessions\CoachingSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListCoachingSessions extends ListRecords
{
    protected static string $resource = CoachingSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Session')
                ->modalHeading('Add Coaching Session')
                ->modalSubmitActionLabel('Save')
                ->modalWidth(Width::Large)
                ->closeModalByClickingAway(false),
        ];
    }
}
