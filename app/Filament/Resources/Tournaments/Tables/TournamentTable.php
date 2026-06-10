<?php

namespace App\Filament\Resources\Tournaments\Tables;

use App\Filament\Resources\Tournaments\Schemas\TournamentInfolist;
use App\Models\Tournament;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TournamentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Tournament')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Location')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('format')
                    ->label('Format')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'swiss' => 'Swiss',
                        'round_robin' => 'Round Robin',
                        'king_of_the_hill' => 'King of the Hill',
                        default => $state,
                    }),
                TextColumn::make('division.name')
                    ->label('Division')
                    ->placeholder('All'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'upcoming' => 'info',
                        'ongoing' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    }),
                IconColumn::make('isf_rated')
                    ->label('ISF Rated')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'upcoming' => 'Upcoming',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                    ]),
                SelectFilter::make('division_id')
                    ->label('Division')
                    ->relationship('division', 'name'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modal()
                        ->url(null)
                        ->modalCancelAction(false)
                        ->modalAlignment('center')
                        ->modalWidth(Width::Large)
                        ->schema(fn ($livewire, Tournament $record): array => TournamentInfolist::configure(
                            Schema::make($livewire)->model($record)->record($record),
                        )->getComponents(withActions: false)),
                    EditAction::make()
                        ->modalHeading('Edit Tournament')
                        ->modalSubmitActionLabel('Save')
                        ->modalWidth(Width::Large)
                        ->closeModalByClickingAway(false),
                    DeleteAction::make(),
                ])
                    ->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->dropdown(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-trophy')
            ->emptyStateHeading('No Tournaments')
            ->emptyStateDescription('Add a tournament to get started.')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Add Tournament')
                    ->modalHeading('Add Tournament')
                    ->modalSubmitActionLabel('Save')
                    ->modalWidth(Width::Large)
                    ->closeModalByClickingAway(false)
                    ->visible(fn () => ! Tournament::exists()),
            ]);
    }
}
